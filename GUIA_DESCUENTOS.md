# Guía de Descuentos - Sistema BeToge

## Cómo Crear Descuentos para Productos Específicos

### Acceso al Módulo
1. Inicia sesión en el sistema
2. En el menú lateral, haz clic en **"Descuentos"** (icono de etiqueta)
3. Haz clic en el botón **"Nuevo Descuento"**

---

## Tipos de Descuentos según Ámbito

### 1. Descuento para Todo el Carrito/Orden
**Cuándo usar:** Cuando quieres que el descuento aplique al total de la compra.

**Ejemplo:** "15% de descuento en toda la tienda"

**Pasos:**
1. En "Ámbito de Aplicación", selecciona: **"Todo el Carrito/Orden"**
2. Configura el tipo y valor del descuento
3. (Opcional) Agrega código de descuento o déjalo vacío para descuento automático

---

### 2. Descuento para Productos Específicos ⭐
**Cuándo usar:** Cuando quieres aplicar descuento solo a ciertos productos.

**Ejemplo:** "20% de descuento en camisetas deportivas"

**Pasos:**
1. En "Ámbito de Aplicación", selecciona: **"Productos Específicos"**
2. Aparecerá un selector múltiple con todos tus productos activos
3. Mantén presionado **Ctrl** (o **Cmd** en Mac) y haz clic en cada producto que quieres incluir
4. Configura el resto de opciones (tipo, valor, código, etc.)
5. Guarda el descuento

**Tip:** Si tienes muchos productos de la misma categoría, es más fácil usar "Categorías Específicas"

---

### 3. Descuento para Categorías Específicas
**Cuándo usar:** Cuando quieres aplicar descuento a todos los productos de una o varias categorías.

**Ejemplo:** "10% en toda la categoría Ropa Deportiva"

**Pasos:**
1. En "Ámbito de Aplicación", selecciona: **"Categorías Específicas"**
2. Aparecerá un selector múltiple con todas tus categorías activas
3. Mantén presionado **Ctrl** (o **Cmd** en Mac) y haz clic en cada categoría
4. Todos los productos de esas categorías tendrán el descuento aplicado
5. Guarda el descuento

---

## Configuración del Descuento

### Tipo y Valor
- **Porcentaje (%):** Ej: 15 = 15% de descuento
- **Monto Fijo ($):** Ej: 5000 = $5,000 de descuento
- **Descuento Máximo:** Límite en pesos (útil para porcentajes)

### Código del Descuento
- **Con código:** El cliente debe ingresar el código en el carrito (ej: `VERANO2024`)
- **Sin código (vacío):** Se aplica automáticamente cuando cumple las condiciones

### Requisitos de Aplicación
- **Monto Mínimo de Compra:** Ej: $50,000 - el carrito debe sumar mínimo esa cantidad
- **Cantidad Mínima de Productos:** Ej: 2 - debe haber al menos 2 productos en el carrito

### Límites de Uso
- **Límite de Usos Total:** Cuántas veces puede usarse el descuento en total
- **Límite por Cliente:** Cuántas veces puede usar el descuento cada cliente
- **Prioridad:** Si hay múltiples descuentos, se aplica primero el de mayor prioridad

### Vigencia
- **Fecha de Inicio:** Desde cuándo está disponible (vacío = inmediato)
- **Fecha de Fin:** Hasta cuándo está disponible (vacío = sin expiración)

### Opciones Adicionales
- ✅ **Activo:** El descuento está disponible para uso
- ✅ **Acumulable:** Se puede combinar con otros descuentos
- ✅ **Solo Primera Compra:** Solo para clientes que compran por primera vez

---

## Ejemplos Prácticos

### Ejemplo 1: 20% en Camisetas Rojas (Producto Específico)
```
Nombre: Descuento Camisetas Rojas
Código: (vacío - automático)
Ámbito: Productos Específicos
  └─ Seleccionar: Camiseta Roja Talla M, Camiseta Roja Talla L
Tipo: Porcentaje
Valor: 20
Activo: ✓
```

### Ejemplo 2: $10,000 en compras sobre $100,000 (Con Código)
```
Nombre: Descuento Compra Mayor
Código: COMPRA100
Ámbito: Todo el Carrito/Orden
Tipo: Monto Fijo
Valor: 10000
Monto Mínimo: 100000
Límite por Cliente: 1
Activo: ✓
```

### Ejemplo 3: 15% en Categoría Deportes (Categoría Específica)
```
Nombre: Promoción Deportes
Código: DEPORTE15
Ámbito: Categorías Específicas
  └─ Seleccionar: Ropa Deportiva, Zapatos Deportivos
Tipo: Porcentaje
Valor: 15
Descuento Máximo: 30000
Fecha Fin: 31/12/2024
Activo: ✓
```

### Ejemplo 4: 2x1 en Producto Específico (Futuro)
```
Nombre: 2x1 Camiseta Blanca
Ámbito: Productos Específicos
  └─ Seleccionar: Camiseta Blanca
Tipo: 2x1
Cantidad Mínima: 2
Activo: ✓
```

---

## Cómo Funciona en la Tienda

### Descuentos con Código
1. El cliente agrega productos al carrito
2. En el carrito, ingresa el código de descuento
3. Hace clic en "Aplicar"
4. El sistema valida:
   - ✓ El código existe y está activo
   - ✓ Cumple con monto/cantidad mínima
   - ✓ No ha excedido límites de uso
   - ✓ Está dentro de la vigencia
5. Si todo es válido, muestra el descuento aplicado

### Descuentos Automáticos (Sin Código)
1. El cliente agrega productos al carrito
2. El sistema automáticamente:
   - Busca descuentos automáticos activos (sin código)
   - Verifica si aplican a los productos del carrito
   - Valida requisitos (monto, cantidad, etc.)
   - Aplica el descuento automáticamente
3. El descuento se muestra en el resumen del carrito

---

## Gestión de Descuentos

### Ver Descuentos
- **Lista completa:** Muestra todos los descuentos con información clave
- **Estadísticas:** Total, activos, con código, automáticos
- **Estado:** Toggle para activar/desactivar rápidamente

### Editar Descuento
1. En la lista, haz clic en el icono de lápiz (editar)
2. Modifica los campos necesarios
3. **Precaución:** Si el descuento ya tiene usos, verás una alerta
4. Guarda los cambios

### Eliminar Descuento
1. Haz clic en el icono de papelera (eliminar)
2. Confirma la acción
3. El descuento se elimina permanentemente

### Activar/Desactivar
- Usa el switch en la columna "Estado"
- Se actualiza inmediatamente
- Los descuentos inactivos no están disponibles en la tienda

---

## Mejores Prácticas

### 1. Nombres Descriptivos
✅ **Bueno:** "15% Descuento Camisetas Verano 2024"
❌ **Malo:** "Desc1"

### 2. Códigos Memorables
✅ **Bueno:** `VERANO15`, `BIENVENIDO10`, `PRIMERACOMPRA`
❌ **Malo:** `X7K9P2`, `ABC123`

### 3. Fechas de Vigencia
- Siempre establece fecha de fin para promociones temporales
- Revisa periódicamente los descuentos vencidos

### 4. Límites de Uso
- Establece límites para evitar abuso
- Para descuentos de bienvenida: límite de 1 por cliente

### 5. Prioridad
- Descuentos más generosos = mayor prioridad
- Ejemplo: 30% (prioridad 3) > 15% (prioridad 2) > 10% (prioridad 1)

### 6. Pruebas
- Antes de activar un descuento, pruébalo en la tienda
- Verifica que se aplique correctamente
- Comprueba los cálculos del monto descontado

---

## Solución de Problemas

### El descuento no aparece en la tienda
- ✓ Verifica que esté **Activo**
- ✓ Revisa las fechas de vigencia
- ✓ Comprueba que no haya excedido el límite de usos
- ✓ Para descuentos de producto/categoría, asegúrate de haber seleccionado los items

### El código no funciona
- ✓ Verifica que el código esté bien escrito (es case-sensitive)
- ✓ Comprueba que el carrito cumpla con el monto/cantidad mínima
- ✓ Revisa que el cliente no haya excedido el límite personal

### El descuento aplica a productos incorrectos
- ✓ Revisa el "Ámbito de Aplicación"
- ✓ Para descuentos de producto, verifica la lista seleccionada
- ✓ Para descuentos de categoría, asegúrate que los productos estén en esa categoría

---

## API y Desarrollo

### Estructura en Base de Datos
- **descuentos:** Tabla principal con configuración
- **descuento_producto:** Relación muchos-a-muchos con productos
- **descuentos_aplicados:** Historial de uso

### Campos JSON
- `productos_aplicables`: Array de IDs de productos
- `categorias_aplicables`: Array de IDs de categorías
- `descuentos_aplicados` (en carrito): Array con descuentos aplicados

---

## Soporte

Para más información o soporte técnico, contacta al administrador del sistema.

**Documentación generada para BeToge E-commerce Platform**
**Versión:** 1.0
**Fecha:** Octubre 2024
