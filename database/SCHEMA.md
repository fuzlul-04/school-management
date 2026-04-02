# School Management ERP - Database Schema

## ER Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              USERS & AUTH                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   ┌──────────┐       ┌──────────┐       ┌─────────────┐                   │
│   │   roles  │       │  users   │       │ permissions │                   │
│   ├──────────┤       ├──────────┤       ├─────────────┤                   │
│   │ id       │◄──────│ role_id  │       │ id          │                   │
│   │ name     │       │ id       │       │ name        │                   │
│   │ slug     │       │ name     │       │ slug        │                   │
│   │ priority │       │ email    │       │ module      │                   │
│   └────┬─────┘       │ phone    │       └──────┬──────┘                   │
│        │             │ status   │              │                          │
│        │             └────┬─────┘              │                          │
│        │                  │                    │                          │
│        │            ┌────┴─────┐         ┌────┴─────┐                    │
│        │            │ role_perm│         │user_perm │                    │
│        │            └──────────┘         └──────────┘                    │
│        │                                                             │
│        └───────────────────────────────────────────────────────────────►│
│                     TEACHERS & STUDENTS                                   │
│                                                                             │
│   ┌──────────┐       ┌──────────┐       ┌────────────┐                     │
│   │ teachers │       │ students │       │ guardians  │                    │
│   ├──────────┤       ├──────────┤       ├────────────┤                    │
│   │ id       │       │ id       │       │ id         │                    │
│   │ user_id  │◄──────│ user_id  │       │ user_id    │                    │
│   │ emp_id   │       │ student_id      │ first_name │                    │
│   │ first_nm│       │ class_id │◄──────│ phone      │                    │
│   │ join_dt  │       │ section_id       └─────┬──────┘                    │
│   │ salary   │       │ academic_yr_id          │                          │
│   └────┬─────┘       └───────┬──────┘            │                          │
│        │                     │                     │                          │
│        │                ┌────┴─────┐          ┌────┴─────┐                 │
│        │                │stud_guard│          │ student  │                 │
│        │                └──────────┘          └──────────┘                 │
│        │                                                              │
│        └───────────────────────────────────────────────────────────────►│
│                           ACADEMIC STRUCTURE                              │
│                                                                             │
│   ┌──────────────┐  ┌────────┐  ┌─────────┐  ┌─────────┐                 │
│   │ academic_yr │  │ classes│  │ sections│  │ subjects │                 │
│   ├──────────────┤  ├────────┤  ├─────────┤  ├─────────┤                 │
│   │ id           │  │ id     │  │ id      │  │ id       │                 │
│   │ start_year   │  │ acad_id│◄─│ class_id│  │ code     │                 │
│   │ end_year     │  │ name   │  │ name    │  │ name     │                 │
│   │ is_current   │  │ section│  └────┬────┘  │ full_mark│                 │
│   └──────┬───────┘  └────┬───┘       │       └────┬────┘                 │
│          │              │            │            │                      │
│          │         ┌────┴────┐       │       ┌────┴─────┐                │
│          │         │class_sub│       │       │teacher_sub│               │
│          │         └─────────┘       │       └───────────┘                │
│          │                           │                                    │
│          │                    ┌──────┴──────┐                             │
│          │                    │ teacher_clas │                            │
│          │                    └─────────────┘                             │
│          │                                                              │
│          └───────────────────────────────────────────────────────────────►│
│                           ATTENDANCE                                       │
│                                                                             │
│   ┌─────────────┐                                                        │
│   │ attendances │                                                        │
│   ├─────────────┤                                                        │
│   │ id          │                                                        │
│   │ student_id  │◄──────────────────────────────────────┐               │
│   │ class_id    │◄────────────────┐                    │               │
│   │ section_id  │◄─────┐          │                    │               │
│   │ subject_id  │◄─────┴──────────┼────────────────────┤               │
│   │ teacher_id  │◄────────┐        │                    │               │
│   │ date        │        │        │                    │               │
│   │ status      │        │        │                    │               │
│   └─────────────┘        │        │                    │               │
│                          │        │                    │               │
│                          └────────┼────────────────────┘               │
│                                   │                                       │
└───────────────────────────────────┼───────────────────────────────────────┘
                                    │
┌───────────────────────────────────┼───────────────────────────────────────┐
│                           EXAMS & RESULTS                                 │
│                                   │                                       │
│   ┌────────┐    ┌────────┐       │    ┌────────┐                        │
│   │  exams │    │  marks  │       │    │results │                        │
│   ├────────┤    ├────────┤       │    ├────────┤                        │
│   │ id     │◄───┤ exam_id │◄──────┼────│ exam_id│                        │
│   │ name   │    │ stud_id │◄──────┘    │ stud_id│                        │
│   │ type   │    │ subj_id │◄───────────┘ │ total_mk│                       │
│   │ start  │    │ class_id│             │ gpa    │                       │
│   │ end    │    │ acad_yr  │             │ rank   │                       │
│   └────────┘    └─────────┘             └────────┘                       │
│                                   │                                       │
└───────────────────────────────────┼───────────────────────────────────────┘
                                    │
┌───────────────────────────────────┼───────────────────────────────────────┐
│                           FINANCE                                          │
│                                   │                                       │
│   ┌──────────┐  ┌────────────┐    │    ┌──────────┐                       │
│   │ fee_types│  │ fee_struct │    │    │ invoices │                       │
│   ├──────────┤  ├────────────┤    │    ├──────────┤                       │
│   │ id       │◄─┤ fee_type_id│    │    │ id        │                       │
│   │ name     │  │ class_id   │◄───┼────│ student_id│                       │
│   │ type     │  │ acad_yr_id │◄───┘    │ class_id  │                       │
│   └──────────┘  │ amount     │         │ total_amt │                       │
│                  └────────────┘         │ paid_amt  │                       │
│                                          │ status    │                       │
│         ┌─────────────────────────┐       └─────┬────┘                       │
│         │      invoice_items      │             │                           │
│         ├─────────────────────────┤       ┌─────┴─────┐                    │
│         │ invoice_id              │◄──────│ payments  │                     │
│         │ fee_type_id             │       ├───────────┤                     │
│         │ amount                  │       │ id        │                     │
│         └─────────────────────────┘       │ amount    │                     │
│                                           │ method    │                     │
│         ┌─────────────────────────┐       │ gateway   │                     │
│         │      transactions      │       └───────────┘                     │
│         ├─────────────────────────┤                                        │
│         │ id                      │       ┌───────────────┐                │
│         │ payment_id              │◄──────│  payment_logs  │                │
│         │ student_id              │       ├───────────────┤                │
│         │ amount                  │       │ gateway_resp  │                │
│         │ method                  │       │ payload       │                │
│         │ gateway                 │       └───────────────┘                │
│         └─────────────────────────┘                                        │
│                                   │                                       │
└───────────────────────────────────┼───────────────────────────────────────┘
                                    │
┌───────────────────────────────────┼───────────────────────────────────────┐
│                           OTHERS                                           │
│                                   │                                       │
│   ┌──────────┐  ┌────────────┐    │    ┌──────────┐                       │
│   │  notices │  │notifications│   │    │audit_logs│                       │
│   ├──────────┤  ├────────────┤    │    ├──────────┤                       │
│   │ id       │  │ id         │    │    │ id        │                       │
│   │ user_id  │◄─┤ user_id    │◄───┼────│ user_id   │◄────┐                │
│   │ title    │  │ is_read    │    │    │ event     │     │                │
│   │ content  │  │ read_at    │    │    │ old/new   │     │                │
│   │ type     │  └────────────┘    │    │ ip_addr   │     │                │
│   └──────────┘                    │    └───────────┘     │                │
│                                    │                      │                │
│                                    └──────────────────────┘                │
│                                                                             │
│   ┌─────────────┐  ┌──────────────┐                                       │
│   │ admissions  │  │   payments   │                                       │
│   ├─────────────┤  ├──────────────┤                                       │
│   │ application│  │              │                                       │
│   │ student_id  │──│──────────────│                                       │
│   │ class_id    │  │              │                                       │
│   │ status      │  │              │                                       │
│   └─────────────┘  └──────────────┘                                       │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

## Database Tables Summary

### 1. Users & Roles (4 tables)
| Table | Description |
|-------|-------------|
| `users` | Main authentication table with role, phone, status, SoftDeletes |
| `roles` | Admin, Teacher, Student, Guardian with priority |
| `permissions` | Granular permissions by module |
| `role_permission` | Role ↔ Permission mapping |
| `user_permission` | Direct user permission override |

### 2. Academic Structure (5 tables)
| Table | Description |
|-------|-------------|
| `academic_years` | Year tracking with is_current flag |
| `classes` | Class levels (Play to Class 10) |
| `sections` | Sections (A, B, C) per class |
| `subjects` | Subjects with Bangla names, marks |
| `class_subject` | Subject-class mapping |
| `teacher_subjects` | Teacher-subject assignment |
| `teacher_classes` | Teacher-class-section-subject mapping |

### 3. People (4 tables)
| Table | Description |
|-------|-------------|
| `teachers` | Employee info, salary, qualifications |
| `students` | Academic profile, SSC details |
| `guardians` | Parent/guardian contact info |
| `student_guardian` | Many-to-many with is_primary flag |

### 4. Academic Process (4 tables)
| Table | Description |
|-------|-------------|
| `admissions` | Application tracking, merit position |
| `attendances` | Daily attendance by subject |
| `exams` | Exam schedules |
| `marks` | Subject-wise marks entry |
| `results` | Computed GPA, rank |

### 5. Finance (5 tables)
| Table | Description |
|-------|-------------|
| `fee_types` | Tuition, exam fee, etc. |
| `fee_structures` | Fee by class/year |
| `invoices` | Generated invoices |
| `invoice_items` | Line items |
| `payments` | Payment records |

### 6. Transactions (2 tables)
| Table | Description |
|-------|-------------|
| `transactions` | All financial movements |
| `payment_logs` | Gateway responses (Bkash, Nagad) |

### 7. Communication (3 tables)
| Table | Description |
|-------|-------------|
| `notices` | School notices, visibility filters |
| `notifications` | User notifications |
| `audit_logs` | Activity tracking |

## Key Relationships

### User → Role → Permissions
- User belongs to one Role
- Role has many Permissions (via role_permission)
- User can have direct Permission override (via user_permission)

### Student → Academic Flow
- Student belongs to Class, Section, AcademicYear
- Student has many Guardians (via student_guardian)
- Student has Attendances, Marks, Results, Invoices

### Teacher → Teaching Assignment
- Teacher belongs to User
- Teacher has many TeacherSubjects, TeacherClasses
- Teacher marks Attendances, Marks

### Finance Flow
- FeeType → FeeStructure → Invoice → InvoiceItems
- Invoice → Payments → Transactions
- All payment gateway responses logged in PaymentLogs

## Bangladesh-Specific Fields

| Field | Table | Purpose |
|-------|-------|---------|
| `bangla_name` | users, teachers, students, guardians, subjects | Bengali names |
| `birth_certificate_number` | students, teachers | NID alternatives |
| `ssc_roll`, `ssc_registration`, `ssc_gpa` | students, admissions | SSC exam details |
| `religion` | students, teachers | Required for BD schools |
| `blood_group` | students, teachers | Medical info |
| `phone` (+880 format) | All people tables | Bangladesh format |

## Indexes for Performance

| Table | Indexes |
|-------|---------|
| `users` | status, role_id, email, phone |
| `students` | student_id, class_id, academic_year_id |
| `teachers` | employee_id, nid_number |
| `attendances` | (student_id, subject_id, date) unique, date+class, student+year |
| `marks` | (student_id, exam_id, subject_id) unique |
| `invoices` | invoice_number, student+status, status+due_date |
| `transactions` | transaction_number, type+status, created_at |

## SoftDeletes Applied To
- users (for account deactivation)
- teachers (employee records)
- students (student records)
- guardians (guardian records)

## Constraints

### Unique Constraints
- users.email, users.phone
- roles.slug, permissions.slug
- students.student_id, teachers.employee_id
- attendances(student_id, subject_id, date)
- marks(student_id, exam_id, subject_id)
- results(student_id, exam_id, academic_year_id)

### Foreign Key Actions
- Most: ON DELETE CASCADE
- user_id in users table: ON DELETE SET NULL (role removal)
- section_id, subject_id: ON DELETE SET NULL (optional relations)

## Quick Start

```bash
# Run migrations
php artisan migrate

# Seed data
php artisan db:seed

# Check tables
php artisan tinker
>>> Schema::getColumnListing('users')
```