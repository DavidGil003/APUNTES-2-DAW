<body>
<div class="container">
    <header>
        <h1>📇 Agenda de Contactos</h1>
        <p>Gestiona tus contactos de manera sencilla y eficiente</p>
    </header>

    <div class="content">
        <!-- Mensajes de éxito/error -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                ✓ <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                ✗ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para añadir contacto -->
        <div class="form-section">
            <h2>➕ Añadir Nuevo Contacto</h2>
            <form method="POST" action="index.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nombre *</label>
                    <input type="text" id="name" name="name" required placeholder="Ej: Juan Pérez">
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required placeholder="Ej: juan@ejemplo.com">
                </div>

                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="tel" id="phone" name="phone" placeholder="Ej: +34 600 123 456">
                </div>

                <div class="form-group">
                    <label for="avatar">Avatar (Opcional - JPG, PNG, GIF - Máx. 2MB)</label>
                    <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/gif">
                </div>

                <button type="submit" name="add_contact" class="btn btn-primary">Añadir Contacto</button>
            </form>
        </div>

        <!-- Buscador -->
        <div class="search-section">
            <h2 style="color: #333; margin-bottom: 15px;">🔍 Buscar Contactos</h2>
            <form method="GET" action="index.php" class="search-form">
                <input type="text" name="q" placeholder="Buscar por nombre..." 
                       value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                <button type="submit" class="btn btn-search">Buscar</button>
            </form>
        </div>

        <!-- Lista de contactos -->
        <div>
            <h2 class="section-title">
                👥 Contactos 
                <?php if (isset($filteredContacts)): ?>
                    (<?php echo count($filteredContacts); ?> encontrados)
                <?php elseif (isset($_SESSION['contacts'])): ?>
                    (<?php echo count($_SESSION['contacts']); ?>)
                <?php endif; ?>
            </h2>

            <?php if (empty($filteredContacts ?? $_SESSION['contacts'] ?? [])): ?>
                <div class="no-contacts">
                    <p>📭 No hay contactos registrados</p>
                    <p style="font-size: 0.9em; margin-top: 10px;">Añade tu primer contacto usando el formulario de arriba</p>
                </div>
            <?php else: ?>
                <div class="contacts-grid">
                    <?php 
                    // Si $filteredContacts está vacío, mostrar todos los contactos de la sesión.
                    $contacts = !empty($filteredContacts) ? $filteredContacts : ($_SESSION['contacts'] ?? []);
                    foreach ($contacts as $index => $contact): 
                    ?>
                        <div class="contact-card <?php echo $contact->getFavorite() ? 'favorite' : ''; ?>">
                            <a href="index.php?action=toggle_fav&i=<?php echo $index; ?><?php echo isset($_GET['q']) && $_GET['q'] !== '' ? '&q=' . urlencode($_GET['q']) : ''; ?>" class="favorite-btn">
                                <?php echo $contact->getFavorite() ? '⭐' : '☆'; ?>
                            </a>

                            <div class="contact-header">
                                <?php if ($contact->getAvatarPath()): ?>
                                    <img src="<?php echo htmlspecialchars($contact->getAvatarPath()); ?>" 
                                         alt="Avatar de <?php echo htmlspecialchars($contact->getName()); ?>" 
                                         class="avatar">
                                <?php else: ?>
                                    <div class="avatar-placeholder">
                                        <?php echo strtoupper(substr($contact->getName(), 0, 1)); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="contact-info">
                                    <h3><?php echo htmlspecialchars($contact->getName()); ?></h3>
                                    <?php if ($contact->getFavorite()): ?>
                                        <span style="color: #ffc107; font-size: 0.9em;">★ Favorito</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="contact-details">
                                <p><strong>📧 Email:</strong> <?php echo htmlspecialchars($contact->getEmail()); ?></p>
                                <?php if ($contact->getPhone()): ?>
                                    <p><strong>📱 Teléfono:</strong> <?php echo htmlspecialchars($contact->getPhone()); ?></p>
                                <?php endif; ?>
                            </div>

                            <div style="padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 0.85em; color: #666;">
                                <?php echo $contact->getSummary(); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>