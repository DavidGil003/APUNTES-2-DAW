<body style="display: flex; justify-content: center; background-color: #ADD8E6; font-family: sans-serif;">

    <form method="post" action="index.php" style="width: 600px;">
        <h2 style="text-align: center;">PAISES DE LA UNIÓN EUROPEA</h2>
        
        <fieldset style="background-color: #e0f7fa; border: 1px solid #999; padding: 15px;">
            
            <fieldset style="margin-bottom: 15px;">
                <legend style="font-weight: bold;">Listado paises</legend>
                
                <ul style="list-style: none; padding: 0;">
                    <?php if (!empty($paises)): ?>
                        <?php foreach ($paises as $pais): ?>
                            <li style="display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #ccc;">
                                <span style="font-weight: bold; color: blue;"><?= htmlspecialchars($pais['nombre']) ?></span>
                                <span><?= htmlspecialchars($pais['capital']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li style="color: gray;">La lista está vacía.</li>
                    <?php endif; ?>
                </ul>
            </fieldset>

            <fieldset style="margin-bottom: 15px;">
                <legend style="font-weight: bold;">Pais de la unión europea</legend>
                
                <div style="margin-bottom: 10px;">
                    <label style="display: inline-block; width: 80px; font-weight: bold;">Pais:</label>
                    <input type="text" name="nombre" style="width: 200px;" value="">
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="display: inline-block; width: 80px; font-weight: bold;">Capital:</label>
                    <input type="text" name="capital" style="width: 200px;" value="">
                </div>

                <div style="margin-top: 10px;">
                    <button type="submit" name="add" value="1"
                        style="padding:8px 16px;background-color:#eee;border:1px solid #999;cursor:pointer;">
                        Añadir país
                    </button>
                    
                    <button type="reset"
                        style="padding:8px 16px;background-color:#eee;border:1px solid #999;cursor:pointer; margin-left: 5px;">
                        Limpiar Campos
                    </button>
                </div>
            </fieldset>

            <fieldset>
                <legend style="font-weight: bold;">Vaciar Listado</legend>
                <button type="submit" name="vaciar" value="1"
                    style="padding:8px 16px;background-color:#ffcccc;border:1px solid #f00;cursor:pointer; color: red;">
                    Vaciar
                </button>
            </fieldset>

        </fieldset>
    </form>
</body>