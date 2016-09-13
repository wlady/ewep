<?xml version="1.0" encoding="UTF-8" ?>
<LoginUser>
    <telephon>{{ user.phone }}</telephon>
    <password>{{ user.password | ebase64 }}</password>
</LoginUser>
