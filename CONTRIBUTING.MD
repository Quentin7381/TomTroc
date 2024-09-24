# Contributing to the Development of this Project

## Information

This project started with a gitflow workflow. As only one person is working on it, the workflow has been simplified to a single branch named `develop`.

If other people start contributing to the project, the workflow will be updated to a more adequate one.

As only one release will be done in its lifetime, we won't create any release or production branch.

## GIT Conventions

### Branches

- `develop`

### Workflow

This project uses a single branch : `develop`.

### Ids

Later refered to as `<id>`, the id is the JIRA ticket identifier.

### Commit naming

The feature must be named as folows: `<id> [description]`. This will be referred to as `<name>`.

The fix must be named as follows: `<id> Fix : [description]`. This will be referred to as `<name>`.

## Code Conventions

### Language

Comments, variable names, function names, etc., must be in English.

### Nomenclatures

note to the corrector: I may have switched between camelCase and snake_case in the project. If any guilty, it is Drupal (and not me :p), with its never ending convention switches...

- `Variables`: camelCase
- `Functions`: camelCase
- `Classes`: PascalCase
- `Constants`: SNAKE_CASE

### Tests

The practice of TDD is highly recommended. But they weren't done, because as in most of the projects, deadlines > tests.

### Documentation

Classes and methods must be documented using JSDoc-style comments.