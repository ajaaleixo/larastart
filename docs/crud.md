###CRUD Generator

Available options to describe a resource:
BestPractice: Resource names should NOT use plural
TODO:
- Add model parameter validators and error messages for each validator (to be added in each controller)

"name" The resource name
"description" "The User"
"model" The data model structure
    Model options:
    "_timestamps" (boolean) - To enable "created_at" and "updated_at"
    "_hasOne" (string) - The name of the resource that should be within the same file - relation 1:1
    "_hasMany" (string) - The name of the resource that should be within the same file - relation 1:n
    "_belongToMany" (string) - The name of the resource that should be within the same file - relation n:n
    "_softDeletes" (boolean) - Rather to use soft deletes or not
    First element will act as primary key
    
    
    
    TODO: 
    
    Relações entre resources
    
    Validations a usar no controller para post, put e patch
    
    post, put e patch
    

