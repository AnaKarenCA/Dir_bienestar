<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>

    <style>
        body{
            font-family: Arial;
            background:#f5f5f5;
        }

        .login{
            width:400px;
            margin:100px auto;
            background:white;
            padding:25px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,.1);
        }

        input{
            width:100%;
            padding:10px;
            margin-bottom:10px;
        }

        button{
            width:100%;
            padding:10px;
        }

        .error{
            color:red;
            margin-bottom:15px;
        }
    </style>
</head>
<body>

<div class="login">

    <h2>Iniciar Sesión</h2>

    <?php if(isset($error)): ?>
        <div class="error">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input
            type="email"
            name="correo"
            placeholder="Correo"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Contraseña"
            required
        >

        <button type="submit">
            Entrar
        </button>

    </form>

</div>

</body>
</html>