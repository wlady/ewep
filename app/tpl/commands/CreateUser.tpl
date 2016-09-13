<?xml version="1.0" encoding="UTF-8" ?>
<CreatUser>
    <telephon>{{ user.phone }}</telephon>
    <email>{{ user.email }}</email>
    <password>{{ user.password | ebase64 }}</password>
</CreatUser>
