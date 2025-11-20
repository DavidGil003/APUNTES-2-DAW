// Gestión de ordenación de tablas
class TableSorter {
  constructor(tableId) {
    this.table = document.querySelector(tableId);
    if (!this.table) return;
    
    this.tbody = this.table.querySelector('tbody');
    this.headers = this.table.querySelectorAll('th.sortable');
    this.currentSort = { column: null, direction: 'asc' };
    
    this.init();
  }

  init() {
    this.headers.forEach((header, index) => {
      header.addEventListener('click', () => this.sortTable(index, header));
    });
  }

  sortTable(columnIndex, header) {
    const rows = Array.from(this.tbody.querySelectorAll('tr'));
    
    // Determinar dirección de ordenación
    if (this.currentSort.column === columnIndex) {
      this.currentSort.direction = this.currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
      this.currentSort.direction = 'asc';
    }
    this.currentSort.column = columnIndex;

    // Actualizar clases de los headers
    this.headers.forEach(h => {
      h.classList.remove('sort-asc', 'sort-desc');
    });
    header.classList.add(this.currentSort.direction === 'asc' ? 'sort-asc' : 'sort-desc');

    // Ordenar filas
    rows.sort((a, b) => {
      const aValue = a.cells[columnIndex].textContent.trim();
      const bValue = b.cells[columnIndex].textContent.trim();

      // Detectar si son números
      const aNum = parseFloat(aValue);
      const bNum = parseFloat(bValue);
      
      let comparison = 0;
      
      if (!isNaN(aNum) && !isNaN(bNum)) {
        comparison = aNum - bNum;
      } else {
        comparison = aValue.localeCompare(bValue, 'es', { numeric: true });
      }

      return this.currentSort.direction === 'asc' ? comparison : -comparison;
    });

    // Reordenar el DOM
    rows.forEach(row => this.tbody.appendChild(row));
  }
}

// Modal de confirmación
class ConfirmModal {
  constructor() {
    this.createModal();
  }

  createModal() {
    // Crear el overlay del modal si no existe
    if (document.getElementById('confirmModal')) return;

    const modalHTML = `
      <div id="confirmModal" class="modal-overlay">
        <div class="modal">
          <h3 id="modalTitle">Confirmar acción</h3>
          <p id="modalMessage">¿Estás seguro de realizar esta acción?</p>
          <div class="modal-buttons">
            <button class="button" id="modalCancel">Cancelar</button>
            <button class="btn-primary" id="modalConfirm">Confirmar</button>
          </div>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);

    this.modal = document.getElementById('confirmModal');
    this.cancelBtn = document.getElementById('modalCancel');
    
    // Cerrar modal al hacer clic fuera
    this.modal.addEventListener('click', (e) => {
      if (e.target === this.modal) {
        this.close();
      }
    });

    // Cerrar modal con botón cancelar
    this.cancelBtn.addEventListener('click', () => this.close());
  }

  show(title, message, onConfirm) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalMessage').textContent = message;
    
    this.modal.classList.add('active');
    
    // Remover listeners anteriores y añadir nuevo
    const confirmBtn = document.getElementById('modalConfirm');
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    
    newConfirmBtn.addEventListener('click', () => {
      this.close();
      if (onConfirm) onConfirm();
    });
  }

  close() {
    this.modal.classList.remove('active');
  }
}

// Gestión de acciones de edición y borrado
class ActionManager {
  constructor(confirmModal) {
    this.confirmModal = confirmModal;
    this.init();
  }

  init() {
    // Gestionar clics en botones de editar
    document.addEventListener('click', (e) => {
      const editBtn = e.target.closest('.btn-edit');
      if (editBtn) {
        e.preventDefault();
        this.handleEdit(editBtn);
      }

      const deleteBtn = e.target.closest('.btn-delete');
      if (deleteBtn) {
        e.preventDefault();
        this.handleDelete(deleteBtn);
      }
    });
  }

  handleEdit(button) {
    const url = button.getAttribute('href');
    const clientId = new URLSearchParams(url.split('?')[1]).get('id');
    
    this.confirmModal.show(
      'Editar Cliente',
      `¿Deseas editar el cliente #${clientId}?`,
      () => {
        window.location.href = url;
      }
    );
  }

  handleDelete(button) {
    const url = button.getAttribute('href');
    const clientId = new URLSearchParams(url.split('?')[1]).get('id');
    
    this.confirmModal.show(
      'Eliminar Cliente',
      `¿Estás seguro de que deseas eliminar el cliente #${clientId}? Esta acción no se puede deshacer.`,
      () => {
        window.location.href = url;
      }
    );
  }
}

// Animación de entrada para las filas de la tabla
function animateTableRows() {
  const rows = document.querySelectorAll('tbody tr');
  rows.forEach((row, index) => {
    row.style.opacity = '0';
    row.style.transform = 'translateY(10px)';
    
    setTimeout(() => {
      row.style.transition = 'opacity 0.3s, transform 0.3s';
      row.style.opacity = '1';
      row.style.transform = 'translateY(0)';
    }, index * 30);
  });
}

// Validación de formularios
function setupFormValidation() {
  const forms = document.querySelectorAll('form');
  
  forms.forEach(form => {
    form.addEventListener('submit', (e) => {
      const requiredInputs = form.querySelectorAll('[required]');
      let isValid = true;

      requiredInputs.forEach(input => {
        if (!input.value.trim()) {
          isValid = false;
          input.style.borderColor = 'var(--danger-color)';
          
          setTimeout(() => {
            input.style.borderColor = '';
          }, 2000);
        }
      });

      if (!isValid) {
        e.preventDefault();
        alert('Por favor, completa todos los campos obligatorios.');
      }
    });
  });
}

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
  // Inicializar ordenación de tabla
  new TableSorter('table');

  // Inicializar modal de confirmación
  const confirmModal = new ConfirmModal();

  // Inicializar gestor de acciones
  new ActionManager(confirmModal);

  // Animación de filas
  animateTableRows();

  // Validación de formularios
  setupFormValidation();

  // Añadir efecto de búsqueda rápida (opcional)
  const searchInput = document.getElementById('quickSearch');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('tbody tr');

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });
  }
});