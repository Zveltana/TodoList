# Contributing Guide
<hr>

## How to contribute to the project ?

### 1. You must clone the project


### 2. Follow the [README](./README.md) to launch the project on your machine


### 3. Create a ticket corresponding to the task you wish to perform

#### <u>Issues</u>

[Issues](https://github.com/Zveltana/TodoList/issues) is the ideal channel for adding instruction tickets for improvements, new features or bug fixes.
To ensure that everyone can work on the project with peace of mind, please ensure that your ticket has not already been created and that you comply with the following restrictions:
* This channel is for project-related requests only.
* Do not insult or use vulgar language when writing your ticket.


### 4. Create a new branch which will respect the naming according to its use :

   * For a new feature or modification :
        ```
        git checkout -b feature/<feature-name>
        ```
   * For a new fix :
       ```
       git checkout -b hotfix/<feature-name>
       ```
<br>

### 5. Good code-writing practice

When writing your codea, you must follow the PHP standards :
* PSR-0
* PSR-1
* PSR-2

#### <u>PSR-0</u>
The PSR-0 standard is designed to facilitate the automatic loading of objects (also known as autoload), by setting up namespaces corresponding to file trees on disk. It's all very coherent and very useful.

#### <u>PSR-1</u>
The PSR-1 standard is based on PSR-0, and adds a few important details :

- Only PHP tags `<?php and <?=` are accepted.
- PHP code must be encoded in UTF-8.
- PHP files can contain symbol declarations or produce writes, but not both.
- Class names must be written in StudlyCaps.
- Constants are written in uppercase.
- Methods are written in camelCase.

#### <u>PSR-2</u>
PSR-2 is a standard for writing code :

- Code is indented with 4 spaces, not with indentations.
- Objects and methods have their opening brace on the next line.
- Control structures have their opening brace on the same line.
- On methods, the static keyword must be used after visibility.

### 6. Commit your changes

#### <u>Commit</u>

When you write your commits, be sure to respect the commit naming convention :

* **build** : Changes that have an effect on the system (installation of new dependencies, composing, npm, environments, etc.)
* **ci** : Continuous integration configuration
* **cd** : Continuous deployment configuration
* **docs** : Documentation changes
* **feat** : New feature
* **fix** : `Hotfix`
* **perf** : Code modification to optimize performance
* **refactor** : Any code modification as part of a refactoring process
* **style** : Corrections specific to coding style (PSR-12)
* **test** : Addition of a new test or correction of an existing test

Example :

```
feat (security): inscription (#ISSUE_ID)
```
<br>

### 7. Code testing and analysis :

Before **push** to GitHub, remember to run a static analysis of the files and all the tests.
<br>
<br>
### 8. Push your new branch containing your code

```
git push origin <branch-name> 
```
<br>

### 9. Create the Pull Request
    
### <u>Pull Request</u>

You must link your RP to the corresponding ticket. In the PR details, add the corresponding ticket closure if only one PR is needed to resolve the ticket  

Example :

```php
    Close #numberofyourticket
```
