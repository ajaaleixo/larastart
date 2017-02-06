# larastart
Larastart is a console application to bootstrap your Laravel App by generating Models, Migrations, Routes and API Controllers automatically.
Proudly fueled by Laravel ^5.4 :)

**Yep, that's right! An API out-of-the-box with a couples of console commands :)**

# Instalation
```
git clone https://github.com/ajaaleixo/larastart
cd larastart
```

# Usage
To use it, you just need to provide a json file (+ formats to come) with the list of Resources that your application will need.

Larastart provides you several commands:
```
Available commands:
  help             Displays help for a command
  list             Lists commands
 make
  make:all         Wrapper to run all the other commands at once
  make:api         Generates API from a resource file
  make:migration   Generates Migrations from a resource file
  make:model       Generates Models from a resource file
  make:seed        Generates Seeds from the spreadsheet file
```

You may use ```make:model``` , or any other command in separate, to generate your intended files:
```
php bin/larastart make:model examples/resources/blog.json ../output_dir
> Processing Models
> Generated 'post's model
> Generated 'author's model
> Finished
```

You can create seeds for you application.

**Important:** The standard is csv file with comma as a separator.

```
php bin/larastart make:seed author examples/resources/author.csv ../output_dir
> Processing Seeds
> Generated 'author's seed
> Finished
```


# Resources File Format

A Resource is a standard file to describe your Data Model Structure, with validation rules. Those files **should** have an array of Resource Items.
You may pass as a resource argument a file or a directory with resource files.

## JSON
Each Resource file, is composed by Resource Items, that are described by:
- name
- description
- model

A **model** is composed by:
- columns: (Mandatory)
- _softDeletes: (Optional) The common softDeletes of Laravel;
- _timestamps: (Optional) The common timestamps of Laravel;
- _table: (Optional) The table name. If not setted, resource.name will be used;
- _{relationship type}: (Optional) Example "_hasOne": "author". You can provide a list of "hasOne" relations or a string for just one;

A **column** is composed by:
- type: (Mandatory) All of the Laravel's column type. Ex: increments, integer, string, text, etc;
- name: (Mandatory) The column name
- length: (Optional) Used as column length in some of the available types;
- _unsigned: (Optional) To describe a column as unsigned;
- _index: (Optional) To add an index on this column. If a string is given it will be the index name. If boolean is given the index name will be automatically generated. If array of strings is given it will generate a compound index of the provided column names

### Resource example for a Blog Application [blog.json](https://github.com/ajaaleixo/larastart/blob/master/examples/resources/blog.json)
