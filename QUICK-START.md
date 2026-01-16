# 🚀 Guía Rápida de Migración de Páginas

**Este es el entry point principal para migrar páginas.** Úsalo como referencia rápida y punto de partida.

## ⚠️ CHECKLIST CRÍTICO - LEE PRIMERO

**ANTES de empezar cualquier migración, revisa el checklist crítico:**
- 📋 **[`CRITICAL-CHECKLIST.md`](./CRITICAL-CHECKLIST.md)** - ⚠️ **OBLIGATORIO LEER** - Puntos críticos que NO pueden olvidarse

## ⚡ Proceso Rápido (3 Pasos)

### 1. Preparar la Migración

```bash
cd llc-v2-pages-migration
```

### 2. Ejecutar Script de Migración (cuando esté listo)

```bash
php migrate-page.php \
  https://bizee.com/[url] \
  [slug]
```

Este script deberá:
- ✅ Descargar el contenido HTML de producción
- ✅ Procesar y subir imágenes a S3
- ✅ Generar estructura básica de la página
- ✅ Aplicar reglas de formato automáticamente

### 3. Revisar y Completar la Página

**⚠️ CRITICAL - NO INVENTAR CONTENIDO:** **NEVER invent, create, or modify content that does not exist in the production page.** All content MUST be extracted exactly as it appears in production.

Debes:
- Revisar el contenido generado
- ⚠️ **OBLIGATORIO:** Verificar que todos los links del contenido original estén incluidos
- Verificar que todos los videos de Wistia estén incluidos como bloques `video`
- Asegurar que las imágenes estén correctamente referenciadas
- Completar cualquier contenido faltante (pero SOLO si existe en producción - nunca inventar)
- Verificar que los componentes usen los tipos correctos

### 4. ⚠️ **NUEVO:** Ejecutar Verificación Automática (Recomendado)

**ANTES** de revisar manualmente, ejecuta el script de verificación automática:

```bash
php verify-page-migration.php \
  content/collections/start_a_business_pages/[slug].md \
  https://bizee.com/[url]
```

Este script verifica automáticamente:
- ✅ UUID único (no duplicado)
- ✅ Campos SEO presentes y correctos
- ✅ Imágenes en S3 (no locales)
- ✅ Links completos (comparación con producción)
- ✅ Videos de Wistia incluidos
- ✅ Componentes con tipos correctos
- ✅ Estructura de bloques correcta
- ✅ Comillas dobles en YAML

**El script mostrará errores y warnings que debes corregir antes de continuar.**

### 5. Verificar Checklist Final

Antes de considerar la migración completa:

- [ ] ⚠️ **CRÍTICO:** ¿El UUID de la página es único? (NUNCA copiar UUID de otra página)
- [ ] ⚠️ **OBLIGATORIO:** ¿TODAS las imágenes están subidas a S3 y referenciadas correctamente?
- [ ] ⚠️ **OBLIGATORIO:** ¿Revisaste que TODOS los links del contenido original están incluidos en formato Bard?
- [ ] ⚠️ **CRÍTICO:** ¿El contenido es exactamente igual al de producción? (no inventado, no modificado)
- [ ] ⚠️ **OBLIGATORIO:** ¿Todos los componentes usan el tipo correcto?
- [ ] ⚠️ **OBLIGATORIO:** ¿Todos los bloques tienen `id`, `type`, `enabled: true` y `version` correctos?
- [ ] ⚠️ **OBLIGATORIO:** ¿Todos los campos SEO están agregados y correctos?
- [ ] ⚠️ **CRÍTICO:** ¿TODOS los strings usan comillas dobles (`"`)? (NUNCA usar comillas simples `'`)
- [ ] ⚠️ **OBLIGATORIO:** ¿Todos los videos de Wistia están incluidos como bloques `video`?

## 📚 Documentación Completa

### Documentos Principales

1. **`README.md`** - Guía general de migración
2. **`QUICK-START.md`** (este archivo) - Entry point rápido
3. **`SCRIPTS-REFERENCE.md`** - Referencia de todos los scripts

### Guías Específicas

- **`README-COMPONENTS.md`** - Componentes disponibles y cómo usarlos
- **`README-STRUCTURE.md`** - Estructura de contenido
- **`README-IMAGES.md`** - Manejo de imágenes
- **`README-LINKS.md`** - Manejo de links
- **`README-SEO.md`** - Campos SEO

## ⚠️ Reglas Críticas (NUNCA Olvidar)

### 1. UUID: CRÍTICO - Debe Ser Único

**NUNCA** copies el UUID de otra página. **SIEMPRE** genera un UUID único para cada página:

- Cada página DEBE tener su propio UUID v4 único
- Si dos páginas comparten el mismo UUID, Statamic solo reconocerá una de ellas
- Siempre genera un nuevo UUID usando `generateUUID()` o una herramienta generadora de UUID

### 2. Componentes: Usar Tipos Correctos

Las páginas usan componentes diferentes a los artículos:

- `cta_section` - Para secciones de call-to-action
- `table_of_contents` - Para tablas de contenido
- `info` - Para bloques de información con items
- `faq` - Para preguntas frecuentes
- `disclaimer_text` - Para disclaimers
- `video` - Para videos de Wistia
- `text` - Para contenido de texto simple
- `info_group_tabs` - Para grupos de información con tabs

### 3. Imágenes: OBLIGATORIO en S3

**NUNCA** dejes imágenes localmente. **SIEMPRE** deben estar en S3.

### 4. Links: OBLIGATORIO Verificar Todos

**SIEMPRE** verificar que todos los links del contenido original estén incluidos en formato Bard.

### 5. Formato: Reglas Estrictas

- **Quotes:** Siempre usar comillas dobles (`"`) para strings en YAML
- **Componentes:** Cada componente debe tener `id`, `type`, `enabled: true` y `version`

## 🔄 Flujo de Trabajo Recomendado

```
1. Leer CRITICAL-CHECKLIST.md
   ↓
2. Extraer contenido de producción con curl
   ↓
3. Identificar y subir imágenes a S3
   ↓
4. Crear estructura básica de la página
   ↓
5. Convertir contenido HTML a bloques apropiados
   ↓
6. Verificar todos los links están en formato Bard
   ↓
7. Configurar SEO desde producción
   ↓
8. Ejecutar verificación automática
   ↓
9. Corregir errores encontrados
   ↓
10. Checklist final
   ↓
11. ✅ Migración completa
```

## 🆘 Si Algo Sale Mal

### Problema: Imágenes no están en S3

**Solución:**
- Verificar que todas las imágenes estén subidas a S3
- Usar rutas correctas de assets en Statamic

### Problema: Componentes con tipo incorrecto

**Solución:**
- Revisar `README-COMPONENTS.md` para ver qué componente usar
- Verificar ejemplos en `content/collections/start_a_business_pages/`

### Problema: Links faltantes o mal formateados

**Solución:**
- Revisar contenido original en el navegador
- Listar todos los links encontrados
- Verificar que cada link esté en la página migrada
- Asegurar formato Bard correcto

## 📝 Notas Importantes

- **NUNCA** guardes imágenes localmente de forma permanente
- **SIEMPRE** usa rutas de S3
- **SIEMPRE** verifica que todos los links estén incluidos
- **SIEMPRE** verifica que todos los videos de Wistia estén incluidos
- **SIEMPRE** usa los componentes correctos según el tipo de contenido

## 🔗 Referencias Rápidas

- **Scripts:** Ver `SCRIPTS-REFERENCE.md`
- **Componentes:** Ver `README-COMPONENTS.md`
- **Estructura:** Ver `README-STRUCTURE.md`
- **Imágenes:** Ver `README-IMAGES.md` ⚠️
- **Links:** Ver `README-LINKS.md` ⚠️

---

**Última actualización:** 2025-01-XX
