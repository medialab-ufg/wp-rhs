### Rede Humaniza SUS - Application Programming Interface (RHS-API)

#### Breve Descrição

API REST da Rede Humaniza SUS.

#### Endpoints

##### 1. User Device

```
/wp-json/rhs/v1/user-device/(?P<device_push_id>[a-zA-Z0-9-]+)
```
Recebe como parâmetro o ID de notificações push do dispositivo mobile, salva esse ID no banco de dados como metadado do usuário (meta_key: device_push_id).

Método:  **`POST`**
Parâmetro: **`device_push_id`**
Callback: **`add_device_push_id`** (string)
Autenticação oauth1: **SIM**

Retorna Associative Array (key, value):
```php
[
  'info' => 'Device ID Registered',
  'device_id' => device_push_id
]
```

##### 2. User

```
/wp-json/rhs/v1/user/(?P<id>[\d]+)
```
Recebe como parâmetro o ID do usuário e retorna suas informações.

Método: **`GET`**
Parâmetro: **`id`** (int)
Callback: **`USER_show`**
Autenticação oauth1: **NÃO**

##### 3. Follow

```
/wp-json/rhs/v1/follow/(?P<id>[\d]+)
```
Recebe como parâmetro o ID do usuário que será seguido ou que deixará de ser seguido. Isto é, se o usuário já segue, isso é desfeito. Se não, passa a seguir.

Método: **`POST`**
Parâmetro: **`id`** (int)
Callback: **`USER_follow`**
Autenticação oauth1: **SIM**

##### 4. Vote

```
/wp-json/rhs/v1/votes/(?P<id>[\d]+)
```
Recebe como parâmetro o ID do post que receberá o voto e, registra esse voto caso o usuário possa votar no post, caso não, um erro é retornado.

Método: **`POST`**
Parâmetro: **`id`** (int)
Callback **`POST_vote`**
Autenticação oauth1: **SIM**