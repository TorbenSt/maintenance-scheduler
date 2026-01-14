# Maintenance Scheduler – SaaS-Oriented Maintenance Planning (WIP)

Maintenance Scheduler is a Laravel-based application exploring how recurring
maintenance workflows can be modeled as a **domain-driven SaaS application**.

The project focuses on **clean domain logic, scheduling rules and long-term
maintainability**, rather than UI polish or feature completeness.

⚠️ **Work in Progress**  
This project is actively evolving and intentionally marked as *WIP*.
It serves as a technical exploration of architecture, domain modeling
and future SaaS patterns.

---

## 🎯 Project Goal

Many businesses rely on recurring maintenance:
- equipment servicing
- safety inspections
- legally required checks
- interval-based tasks

This project explores how such requirements can be represented as
**first-class domain concepts**, instead of ad-hoc calendar entries or spreadsheets.

---

## 🧠 Focus Areas

- Domain-driven modeling of maintenance intervals
- Separation of scheduling logic and persistence
- SaaS-oriented architecture thinking
- Testable business rules
- API-first mindset (future expansion)

---

## 🛠 Tech Stack

- **Framework**: Laravel
- **Database**: MySQL / PostgreSQL
- **Architecture**: Domain-oriented services
- **Testing**: Pest PHP
- **Frontend**: Blade / Livewire (early stage)

---

## 🚧 Current Status

Implemented:
- Core domain concepts (maintenance entities, intervals)
- Initial database schema
- Basic scheduling logic
- Test scaffolding

Planned / In Progress:
- Notification logic
- User & tenant separation
- API endpoints
- UI refinement
- SaaS multi-tenancy considerations

---

## 🧪 Tests

The project uses Pest for testing core domain logic.

```bash
./vendor/bin/pest
