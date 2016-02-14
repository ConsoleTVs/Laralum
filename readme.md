## Laralum - Laravel 5.2 Administration Panel

#### Features
- Add your users / roles colums as you like, we take care of displaying them in the edit page
- User Manager
- Role Manager
- Permission Manager
- Integrated with laravel Auth
- User Settings
- Made for coders

## Installation
Install it using composer
```
composer create-project erik/laralum ProjectName
```

## Simple instructions
To make things simple while i try to build a site and start a documentation, i will tell small and usefull details here.

- You can use the following command to check if a user belongs to a role (Response: true / false):
Example (check if the logged in user is in the role 'Administrator'):
```
$user = Auth::user();
$user->is('Administrator');
```

- You can use the following command to check if a user has a permission in any of his roles (Response: true / false):
Example (check if the logged in user has the permission 'admin.access'):
```
$user = Auth::user();
$user->has('admin.access');
```

- You can use the following command to get all the user's roles (Response: Object):
Example (Get all the roles of the logged in user and display their names):
```
$user = Auth::user();
$roles = $user->roles();
foreach($roles as $role){
  echo $role->name . '<br>';
}
```

- You can use the following command to check if a role has a specific permission (Response: true / false):
Example (Check if the role with an id of 1 have the permission 'admin.access'):
```
$role = Role::findOrFail(1);
$role->has('admin.access');
```

- You can use the following command to get all the role's users (Response: Object):
Example (Get all the users of the role with an id of 1 and display their names):
```
$role = Role::findOrFail(1);
$users = $role->users();
foreach($users as $user){
  echo $user->name . '<br>';
}
```

- You can use the following command to get all the role's permissions (Response: Object):
Example (Get all the permissions of the role with an id of 1 and display their names):
```
$role = Role::findOrFail(1);
$permissions = $role->permissions();
foreach($permissions as $perm){
  echo $perm->name . '<br>';
}
```

- You can use the following command to get all the roles that use a permission (Response: Object):
Example (Get all the roles of the permission 'admin.access' and display their names):
```
$permission = Permission::where('slug', 'admin.access')->first();
$roles = $permission->roles();
foreach($roles as $role){
  echo $role->name . '<br>';
}
```

- You can use the following command to get the type of the permission (Response: Object):
Example (Get the type of the permission 'admin.access'):
```
$permission = Permission::where('slug', 'admin.access')->first();
$type = $permission->type();
```

- You can use the following command to get the permissions from a type (Response: Object):
Example (Get the permissions from the type 'Administration Panel'):
```
$type = Permission_Types::where('type', 'Administration Panel')->first();
$permissions = $type->permissions();
```

## Screenshots

![alt text](http://puu.sh/n3awr/cbb7ad607d.png "Logo Title Text 1")
