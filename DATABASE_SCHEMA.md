Progress update: created a 5-step plan and started schema design. Next I'll show the MySQL table structure for the application.

Schema Overview
- Purpose: capture users (roles), students/parents/staff, school structure, CBT (exams, questions, sessions, attempts), academic records, attendance, fees, and ACL.
- Primary key: use `BIGINT UNSIGNED` `id` auto-increment for main tables.
- Common columns: `created_at`/`updated_at` DATETIME, `deleted_at` DATETIME NULL for soft deletes when needed.
- Use FK constraints for referential integrity; add indexes on FK columns and unique constraints where appropriate.
- Use `JSON` for flexible structured data (e.g., question metadata, exam settings).

Tables (core)

- `users`:
  - `id` BIGINT UNSIGNED PK
  - `name` VARCHAR(191)
  - `email` VARCHAR(191) UNIQUE
  - `password` VARCHAR(255)
  - `phone` VARCHAR(30) NULL
  - `role` ENUM('super_admin','admin','staff','parent','student') or use many-to-many `roles`
  - `is_active` TINYINT(1) DEFAULT 1
  - `meta` JSON NULL
  - `remember_token` VARCHAR(100) NULL
  - `created_at`, `updated_at`, `deleted_at` NULL
  - Indexes: `email` unique, index on `role`, `is_active`

- `roles` (optional if using RBAC):
  - `id`, `name` VARCHAR(100) UNIQUE, `guard_name` VARCHAR(50), `created_at`, `updated_at`

- `role_user` (pivot):
  - `role_id` FK -> `roles.id`, `user_id` FK -> `users.id`, composite PK, indexes

- `permissions` and `permission_role` (optional standard ACL)

- `staff_members`:
  - `id` PK (or `user_id` as PK FK to `users.id`)
  - `user_id` FK -> `users.id` UNIQUE
  - `employee_no` VARCHAR(50) UNIQUE
  - `position` VARCHAR(100)
  - `department` VARCHAR(100)
  - `hire_date` DATE NULL
  - `detail` JSON NULL
  - timestamps, soft delete
  - Index on `employee_no`

- `students`:
  - `id` PK (or `user_id` as PK FK to `users.id`)
  - `user_id` FK -> `users.id` UNIQUE
  - `admission_no` VARCHAR(50) UNIQUE
  - `class_id` FK -> `classes.id`
  - `section` VARCHAR(50) NULL
  - `dob` DATE NULL
  - `gender` ENUM('male','female','other') NULL
  - `status` ENUM('active','inactive','alumni','suspended') DEFAULT 'active'
  - `detail` JSON NULL
  - timestamps
  - indexes: `admission_no`, `class_id`

- `student_parents`:
  - `id`, `student_id` FK -> `students.id`, `parent_user_id` FK -> `users.id` (or `parent_id` referencing `parents`), `relationship` VARCHAR(50), `primary` TINYINT(1) DEFAULT 0
  - composite unique (`student_id`,`parent_user_id`)

- `parents` (optional separate table):
  - `id`, `user_id` FK -> `users.id`, `detail` JSON, timestamps

- `classes`:
  - `id`, `name` VARCHAR(100), `level` VARCHAR(50) NULL, `created_at`, `updated_at`
  - unique (`name`)

- `school_years`:
  - `id`, `name` VARCHAR(50) (e.g., 2025/2026), `start_date` DATE, `end_date` DATE, `is_current` TINYINT(1)

- `subjects`:
  - `id`, `name` VARCHAR(150), `code` VARCHAR(50) NULL, `department` VARCHAR(100) NULL

- `class_subject` (pivot):
  - `id`, `class_id` FK, `subject_id` FK, `teacher_id` FK -> `staff_members.id` NULL, unique composite (`class_id`,`subject_id`)

- `school_clubs`:
  - `id`, `name`, `description`, `created_at`

- `assignments`:
  - `id`, `title`, `description` TEXT, `subject_id` FK, `class_id` FK, `assigned_by` FK -> `staff_members.id`, `due_date` DATETIME NULL, `settings` JSON, timestamps

- `assignment_submissions`:
  - `id`, `assignment_id` FK, `student_id` FK, `submitted_at` DATETIME, `file_url` VARCHAR(255) NULL, `grade` DECIMAL(5,2) NULL, `feedback` TEXT NULL, indexes on (`assignment_id`,`student_id`)

- `attendance`:
  - `id`, `student_id` FK, `date` DATE, `status` ENUM('present','absent','late','excused'), `remarks` VARCHAR(255) NULL
  - unique (`student_id`,`date`)

CBT-specific:

- `exams`:
  - `id` PK
  - `title` VARCHAR(191)
  - `description` TEXT NULL
  - `subject_id` FK
  - `created_by` FK -> `staff_members.id`
  - `class_id` FK NULL (if exam limited to class)
  - `school_year_id` FK
  - `duration_minutes` INT
  - `start_at` DATETIME NULL
  - `end_at` DATETIME NULL
  - `settings` JSON (shuffle questions, negative_marking, pass_mark, attempts_allowed)
  - `status` ENUM('draft','scheduled','running','closed') DEFAULT 'draft'
  - timestamps

- `exam_sections` (optional grouping):
  - `id`, `exam_id`, `title`, `time_limit_minutes` NULL, `shuffle` TINYINT, `ordering` INT

- `questions`:
  - `id` PK
  - `exam_id` FK NULL (if questions can be shared) or `question_bank_id` optional
  - `section_id` FK NULL
  - `type` ENUM('single_choice','multi_choice','true_false','short_text','long_text','file_upload') 
  - `stem` TEXT (the question body)
  - `choices` JSON NULL (for MCQ: choices array with keys)
  - `answer` JSON NULL (canonical answer(s) — for MCQ store correct choice keys, for text store expected answer/meta)
  - `marks` DECIMAL(6,2) DEFAULT 1
  - `negative_marks` DECIMAL(6,2) DEFAULT 0
  - `meta` JSON NULL (attachments, images)
  - `difficulty` TINYINT NULL
  - timestamps
  - Indexes: `exam_id`, `section_id`

- `question_choices` (if structured choice table preferred):
  - `id`, `question_id` FK, `label` VARCHAR(10), `content` TEXT, `is_correct` TINYINT(1) DEFAULT 0, `ordering` INT

- `exam_sessions` (scheduled CBT instances — for proctoring/assigned runs):
  - `id`, `exam_id` FK, `session_key` VARCHAR(100) UNIQUE, `start_at`, `end_at`, `max_participants` INT NULL, `settings` JSON, `created_by` FK

- `exam_attempts` (student runs):
  - `id`, `exam_id` FK, `session_id` FK NULL, `student_id` FK, `started_at` DATETIME, `finished_at` DATETIME NULL, `status` ENUM('in_progress','submitted','auto_submitted','graded','disqualified'), `score` DECIMAL(8,2) NULL, `raw_result` JSON (store detailed per-question data), `ip_address` VARCHAR(45) NULL, `device_info` JSON NULL
  - Indexes: (`exam_id`,`student_id`), (`session_id`)

- `exam_answers`:
  - `id`, `attempt_id` FK -> `exam_attempts.id`, `question_id` FK, `answer` JSON (student response), `is_correct` TINYINT NULL, `marks_awarded` DECIMAL(6,2) NULL, `answered_at` DATETIME
  - Index: `attempt_id`

- `grading` (optional audit):
  - `id`, `attempt_id`, `graded_by` FK -> `staff_members.id`, `graded_at`, `comments`, `score_delta`

Academic records & results:

- `term_results` or `grades`:
  - `id`, `student_id`, `subject_id`, `exam_id` FK NULL, `score` DECIMAL, `grade` VARCHAR(5), `remarks`, `school_year_id`, `term` ENUM('first','second','third') etc., timestamps
  - Indexes: (`student_id`,`school_year_id`)

Finance:

- `fees`:
  - `id`, `name`, `amount` DECIMAL(10,2), `type_id` FK -> `fee_types.id`, `class_id` FK NULL (if class-specific), `due_date` DATE NULL, `recurring` ENUM('none','monthly','termly','yearly') DEFAULT 'none', `meta` JSON
  - timestamps

- `fee_types`:
  - `id`, `name` (tuition, exam, lab), `description`

- `fee_payments`:
  - `id`, `student_id`, `fee_id`, `amount_paid` DECIMAL, `paid_at` DATETIME, `method` VARCHAR(50), `reference` VARCHAR(255), `status` ENUM('pending','completed','failed'), `receipt_url` VARCHAR(255) NULL

Other utilities:

- `personal_access_tokens` (Laravel Sanctum migration already present)
- `notifications`:
  - `id`, `user_id`, `type`, `data` JSON, `read_at` DATETIME NULL, `created_at`
  - Index on `user_id`, `read_at`

- `settings`:
  - `id`, `key` VARCHAR(191) UNIQUE, `value` JSON, `group` VARCHAR(100)

- `logs` / `audit_logs`:
  - `id`, `actor_id` FK -> `users.id`, `action` VARCHAR(100), `target_type` VARCHAR(100), `target_id` BIGINT NULL, `meta` JSON, `created_at`

- `api_tokens` (if custom): `id`, `user_id`, `token`, `expires_at`, `scopes` JSON

Performance & constraints notes
- Use composite unique constraints where necessary (e.g., `student_admission_no`).
- Add FK ON DELETE CASCADE for dependent records where appropriate (e.g., `exam_attempts` -> delete attempts when `students` removed may be undesirable; prefer soft delete).
- Use transactions for exam submission, grading, fee payment operations.

Example column types (quick reference)
- Ids: `BIGINT UNSIGNED` AUTO_INCREMENT
- Strings: `VARCHAR(191)` or `VARCHAR(255)`
- Large text: `TEXT` or `LONGTEXT`
- Booleans: `TINYINT(1)`
- Money/grades: `DECIMAL(10,2)` / `DECIMAL(6,2)`
- JSON: `JSON` (MySQL 5.7+/8.0)
- Timestamps: `DATETIME` or Laravel `timestamp()` with timezone handled in app

Next steps I can take for you (pick one)
- Generate Laravel migration files for the above schema.
- Produce a compact ER diagram (text or mermaid).
- Scaffold Eloquent models and relationships.
Which would you like me to do next?
