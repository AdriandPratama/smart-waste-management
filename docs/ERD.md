
# Smart Waste Management - Database ERD

Dokumentasi ERD untuk database `smart_waste_management`.

## Entity Relationship Diagram

```mermaid
erDiagram
    USERS ||--o{ WASTE_REPORTS : creates
    USERS ||--o{ REPORT_STATUS_HISTORIES : changes
    WASTE_CATEGORIES o|--o{ WASTE_REPORTS : categorizes
    WASTE_REPORTS ||--o{ REPORT_IMAGES : contains
    WASTE_REPORTS ||--o{ REPORT_STATUS_HISTORIES : tracks

    USERS {
        bigint id PK
        varchar name
        varchar email UK
        timestamp email_verified_at
        varchar password
        varchar role
        varchar remember_token
        timestamp created_at
        timestamp updated_at
    }

    WASTE_CATEGORIES {
        bigint id PK
        varchar name UK
        text description
        timestamp created_at
        timestamp updated_at
    }

    WASTE_REPORTS {
        bigint id PK
        bigint user_id FK
        bigint category_id FK
        varchar title
        text description
        decimal latitude
        decimal longitude
        varchar status
        decimal confidence_score
        timestamp created_at
        timestamp updated_at
    }

    REPORT_IMAGES {
        bigint id PK
        bigint report_id FK
        varchar image_path
        timestamp created_at
        timestamp updated_at
    }

    REPORT_STATUS_HISTORIES {
        bigint id PK
        bigint report_id FK
        varchar old_status
        varchar new_status
        bigint changed_by FK
        text notes
        timestamp created_at
        timestamp updated_at
    }

    PERSONAL_ACCESS_TOKENS {
        bigint id PK
        varchar tokenable_type
        bigint tokenable_id
        text name
        varchar token UK
        text abilities
        timestamp last_used_at
        timestamp expires_at
        timestamp created_at
        timestamp updated_at
    }
```

## Table Relationships

| Parent Table | Child Table | Foreign Key |
|---|---|---|
| users | waste_reports | user_id |
| users | report_status_histories | changed_by |
| waste_categories | waste_reports | category_id |
| waste_reports | report_images | report_id |
| waste_reports | report_status_histories | report_id |

## Notes

- PK: Primary Key
- FK: Foreign Key
- UK: Unique Key
- `category_id` pada waste_reports bersifat nullable.
- `personal_access_tokens` menggunakan relasi polymorphic Laravel melalui `tokenable_type` dan `tokenable_id`.
- Tabel bawaan Laravel seperti cache, jobs, sessions, dan migrations tidak ditampilkan karena diagram berfokus pada tabel utama aplikasi.
