# Contributing to the Development of this Project

## GIT Conventions

### Branches

- `master`: main branch of the project, contains production code
- `release/<id>`: release branch, contains code being prepared for the next version
- `dev`: development branch, contains code under development
- `feature/<id>`: feature branch, contains code for developing a new feature
- `fix/<id>`: fix branch, contains code for fixing a bug
- `hotfix/<id>`: hotfix branch, contains code for urgently fixing a critical bug in production

The `<id>` of the branches are the corresponding JIRA ticket identifiers.

### Workflow

This project follows the Gitflow workflow.

### Ids

Later refered to as `<id>`, the id is the JIRA ticket identifier.

### Branch names

The feature and fix must be named as folows: `<id>_featureDescription`. This will be referred to as `<name>`.

### Creating a New Feature / Fix

To create a new feature, use the following command:

```bash
git flow [feature/fix] start <name>
```

This command will create a new branch named `feature/<name>` (or `fix/<name>`) and switch to it.

### Finishing a Feature / Fix

To finish a feature, use the following command:

```bash
git flow [feature/fix] finish <name>
```

This command will merge the branch back to the `dev` branch and delete the feature branch.

### Versioning

The versions should be named as follows: `<major>.<minor>.<patch>`.

### Creating a New Release

Releases are coherent sets of features and fixes that are ready to be deployed to production.

To create a new release, use the following command:

```bash
git flow release start <number>
```

This command will create a new branch named `release/<number>` based on the curent dev branch and switch to it.

### Finishing a Release

Once tested and ready to be deployed, a release can be finished and merged to the `master` with the following command:

```bash
git flow release finish <number>
```

This command will merge the branch back to the `master` and `dev` branches, create a tag for the release, and delete the release branch.

## Code Conventions

### Language

Comments, variable names, function names, etc., must be in English.

### Nomenclatures

- `Variables`: camelCase
- `Functions`: camelCase
- `Classes`: PascalCase
- `Constants`: SNAKE_CASE

### Tests

The practice of TDD is highly recommended. Objects and methods must be testable and unit tested.

### Documentation

Classes and methods must be documented using JSDoc-style comments.