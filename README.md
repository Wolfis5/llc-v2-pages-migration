# LLC V2 Pages - Migration Guide

This directory contains scripts and documentation for migrating pages to the `start_a_business_pages` collection in Statamic CMS.

## 🚀 Quick Start

**Para empezar rápidamente, lee primero:** [`QUICK-START.md`](./QUICK-START.md)

## 🤖 Instrucciones para Cursor AI

**Si vas a usar Cursor AI con un agente para migrar páginas:**
- 📋 **[`CURSOR-AGENT-INSTRUCTIONS.md`](./CURSOR-AGENT-INSTRUCTIONS.md)** - Instrucciones completas para configurar y usar el agente de Cursor

Este documento contiene:
- Instrucciones iniciales para el agente
- Proceso paso a paso para migraciones
- Checklists de verificación
- Instrucciones específicas para imágenes, links, videos, etc.
- Ejemplos de conversación

## ⚠️ CHECKLIST CRÍTICO - LEE PRIMERO

**ANTES de empezar cualquier migración, revisa el checklist crítico:**
- 📋 **[`CRITICAL-CHECKLIST.md`](./CRITICAL-CHECKLIST.md)** - ⚠️ **OBLIGATORIO LEER** - Puntos críticos que NO pueden olvidarse

## 📋 Estructura de las Páginas

Las páginas migradas van a la colección `start_a_business_pages` y usan el blueprint `start_a_business_page`.

### Frontmatter Básico

```yaml
---
id: [UUID v4 único]
blueprint: start_a_business_page
title: 'Page Title'
custom_page_header: false
breadcrumbs: true
breadcrumb_version: breadcrumbs_v1
page_settings_no_index: false
page_settings_hide_breadcrumbs: false
page_settings_hide_footer: false
page_settings_hide_on_production: false
page_settings_enabled_scripts:
  - fullstory
  - ahrefs
seo_title: title
seo_meta_description: custom
seo_custom_meta_title: 'SEO Title from production'
seo_custom_meta_description: 'SEO Description from production'
seo_canonical: none
seo_og_description: general
seo_og_title: title
seo_tw_title: title
seo_tw_description: general
blocks:
  # Array de bloques aquí
---
```

## 🧩 Componentes Disponibles

Las páginas usan diferentes componentes que los artículos. Los principales son:

### 1. `cta_section`
Sección de call-to-action con título, descripción, imagen y botón.

### 2. `trust_badges`
Badges de confianza (Trustpilot, Shopper Approved).

### 3. `info`
Bloques de información con items, puede tener título, descripción y lista de items.

### 4. `table_of_contents`
Tabla de contenidos con items anidados. Puede contener texto, imágenes, videos, botones, etc.

### 5. `disclaimer_text`
Texto de disclaimer/disclaimer.

### 6. `faq`
Preguntas frecuentes con formato FAQ.

### 7. `info_group_tabs`
Grupos de información organizados en tabs.

### 8. `text`
Componente de texto simple (Bard content).

### 9. `components`
Contenedor de componentes.

### 10. `blocks`
Contenedor de bloques anidados.

### 11. `video`
Videos de Wistia.

## 📝 Proceso de Migración

1. **Extraer contenido de producción** usando `curl`
2. **Identificar y subir imágenes a S3**
3. **Crear archivo markdown** en `content/collections/start_a_business_pages/[slug].md`
4. **Convertir contenido HTML a bloques** apropiados
5. **Verificar links** del contenido
6. **Configurar SEO** desde producción
7. **⚠️ CRÍTICO: Agregar ruta a `released-pages.php`** - Agregar `/start-a-business/[slug]` a `app/Routing/migration/released-pages.php`
8. **⚠️ OBLIGATORIO: Ejecutar script de comillas** - Ejecutar `python3 llc-v2-pages-migration/replace_quotes_final.py content/collections/start_a_business_pages/[slug].md` para reemplazar comillas simples por tipográficas
9. **Verificar checklist crítico**

## 📚 Documentación Completa

- **`QUICK-START.md`** - 🚀 Entry point principal (empieza aquí)
- **`CRITICAL-CHECKLIST.md`** - ⚠️ Checklist crítico obligatorio
- **`README-COMPONENTS.md`** - Guía de componentes disponibles
- **`README-STRUCTURE.md`** - Estructura de contenido
- **`README-IMAGES.md`** - Manejo de imágenes
- **`README-LINKS.md`** - Manejo de links
- **`README-SEO.md`** - Campos SEO
- **`SCRIPTS-REFERENCE.md`** - Referencia de scripts

## ⚠️ Reglas Críticas

1. **UUID Único:** Cada página DEBE tener un UUID v4 único. NUNCA copies UUIDs de otras páginas.
2. **NO Inventar Contenido:** TODO el contenido debe ser exacto de producción.
3. **Imágenes en S3:** Todas las imágenes deben estar en S3, no locales.
4. **Verificación de Links:** Verificar todos los links del contenido principal.
5. **Componentes Correctos:** Usar los componentes apropiados para cada tipo de contenido.
6. **SEO Completo:** Incluir todos los campos SEO extraídos de producción.
7. **⚠️ Agregar a Released Pages:** DESPUÉS de crear el archivo markdown, SIEMPRE agregar la ruta `/start-a-business/[slug]` a `app/Routing/migration/released-pages.php`. Sin este paso, la página NO será servida por Statamic.
8. **⚠️ Script de Comillas:** AL FINALIZAR la migración, SIEMPRE ejecutar `python3 llc-v2-pages-migration/replace_quotes_final.py content/collections/start_a_business_pages/[slug].md` para reemplazar comillas simples por tipográficas en campos de texto.

## 🔍 Verificación Automática

Después de migrar una página, ejecuta el script de verificación:

```bash
php verify-page-migration.php \
  content/collections/start_a_business_pages/[slug].md \
  https://bizee.com/[url]
```

---

**Última actualización:** 2025-01-XX
