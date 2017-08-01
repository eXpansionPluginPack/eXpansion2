# How to contribute

Third-party patches are essential for keeping eXpansion great. We simply can't
access the huge number of platforms and myriad configurations for running
eXpansion. We want to keep it as easy as possible to contribute changes that
get things working in your environment. There are a few guidelines that we
need contributors to follow so that we can have a chance of keeping on
top of things.

# Core Framework vs Core Bundles vs Bundles

New functionality is typically directed towards external bundles to provide a slimmer
eXpansion Core, reducing its surface area, and to allow greater freedom for
bundle maintainers to ship releases at their own cadence, rather than
being held to the cadence of eXpansion releases.

Some Core bundles are provided as they are considered assential for the existance
of the controller. 

If you are unsure of whether your contribution should be implemented contact us.

## Getting Started

* Make sure you have a [GitHub account](https://github.com/signup/free)
* Submit a ticket for your issue, assuming one does not already exist.
  * Clearly describe the issue including steps to reproduce when it is a bug.
  * Make sure you fill in the earliest version that you know has the issue.
* Fork the repository on GitHub

## Making Changes

* Create a topic branch from where you want to base your work.
  * This is usually the master branch.
  * Only target release branches if you are certain your fix must be on that
    branch.
  * To quickly create a topic branch based on master; `git checkout -b
    fix/master/my_contribution master`. Please avoid working directly on the
    `master` branch.
* Make commits of logical units.
* Check for unnecessary whitespace with `git diff --check` before committing.
* Make sure your commit messages are in the proper format. #{topic-number} - {description}
* Make sure you have added the necessary tests for your changes.
* Run _all_ the tests to assure nothing else was accidentally broken.

### Documentation

For changes of a trivial nature to comments and documentation, it is not
always necessary to create a new ticket in Jira. In this case, it is
appropriate to start the first line of a commit with '(doc)' instead of
a ticket number.

````
    (doc) Add documentation commit example to CONTRIBUTING

    There is no example for contributing a documentation commit
    to the eXpansion repository. This is a problem because the contributor
    is left to assume how a commit of this nature may appear.

    The first line is a real life imperative statement with '(doc)' in
    place of what would have been the ticket number in a
    non-documentation related commit. The body describes the nature of
    the new documentation or comments added.
````

## Submitting Changes
* Push your changes to a topic branch in your fork of the repository.
* Submit a pull request to the repository in the puppetlabs organization.
* The core team looks at Pull Requests on a regular basis

