# Task Manager

This is a simple task management application developed for Coalition Technologies Resume.

## Description

The base of this project is a Task object CRUD functionality. I use two outside packages for the user interface. Those are DataTables for displaying existing tasks, and Sortable for providing drag-and-drop functionality to objects displayed in the table. The re-ordering of table entries is automatically updated to the "position" column in the database for that object. There is only one relationship in this app, which is a One to Many relationship between Projects and Tasks.

### Installing

After downloading and unzipping the project please run a composer install/update to ensure that the packages are all up-to-date. If using homestead you will then need to add the directory to your .yaml file as well as add the site to your hosts file. Then run a vagrant reload --provision after making changes to your .yaml file. 

### Executing program

You will be initially presented with an empty table. There are two actions at the top of the table. One to create tasks, one to create Projects. Both will prompt you with a modal in which you need to fill all inputs. On save an ajax request will be sent to the database to create those objects, and your page will be refreshed. You will then have the option of editing/deleting tasks, and also re-ordering tasks using the icon to the left of it's name.

## Authors

Jack Bergemann

## Acknowledgments

Inspiration, code snippets, etc.
* [README template](https://gist.github.com/DomPizzie/7a5ff55ffa9081f2de27c315f5018afc#file-readme-template-md)
