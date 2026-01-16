# ⚠️ CHECKLIST CRÍTICO DE MIGRACIÓN DE PÁGINAS

**Este documento contiene los puntos CRÍTICOS que DEBES verificar en CADA migración de página.**

## 🔴 Puntos Críticos que NO Pueden Olvidarse

### 1. ⚠️ **UUID ÚNICO** - CRÍTICO
- ✅ Cada página DEBE tener un UUID v4 único
- ✅ **NUNCA** copies el UUID de otra página
- ✅ Si dos páginas comparten el mismo UUID, Statamic solo reconocerá una
- ✅ Siempre genera un nuevo UUID único

### 1.5. ⚠️ **LINK DEL CTA HERO** - CRÍTICO
- ✅ **OBLIGATORIO:** El link del botón del CTA hero (`cta_section_19`) DEBE ser exactamente el mismo que aparece en producción
- ✅ **NUNCA** asumas que el link tiene parámetros UTM o `entityType` sin verificar primero
- ✅ **SIEMPRE** extrae el link directamente del HTML de producción usando curl o navegador
- ✅ **Formato de verificación:** `curl -s "https://bizee.com/start-a-business/[slug]" | grep -o 'href="[^"]*form-order-now[^"]*"'`
- ✅ **CRÍTICO:** Si el link en producción es simple (`https://orders.bizee.com/form-order-now.php`), NO agregues parámetros
- ✅ **CRÍTICO:** Si el link en producción tiene parámetros, copia TODOS los parámetros exactamente
- ✅ Esta regla aplica SOLO al CTA hero inicial, no al CTA final

### 2. ⚠️ **IMÁGENES** - OBLIGATORIO
- ✅ **Todas las imágenes** deben estar subidas a S3
- ✅ **NUNCA** dejar imágenes locales o URLs externas
- ✅ Verificar que todas las imágenes aparecen como assets en Statamic
- ✅ Usar rutas correctas de S3

### 3. ⚠️ **VERIFICACIÓN DE LINKS** - OBLIGATORIO
- ✅ **PASO 1:** Abre la página de producción en el navegador
- ✅ **PASO 2:** Identifica TODOS los links visibles **SOLO en el contenido principal** (excluir header, footer, sidebar, etc.)
- ✅ **PASO 3:** Compara uno por uno con la página migrada
- ✅ **PASO 4:** Si falta algún link del contenido, agrégalo inmediatamente
- ✅ **ESTA VERIFICACIÓN ES OBLIGATORIA Y DEBE HACERSE AL FINAL DE CADA MIGRACIÓN**
- ✅ Verificar formato correcto de links en Bard

### 4. ⚠️ **NO INVENTAR CONTENIDO** - CRÍTICO
- ✅ **NEVER invent, create, or modify content that does not exist in the production page**
- ✅ All content (headings, paragraphs, lists, descriptions, etc.) MUST be extracted exactly as it appears in production
- ✅ If you cannot find specific content in production, DO NOT create it
- ✅ This is a migration, not content creation
- ✅ Always verify that:
  - All headings match production exactly
  - All paragraphs match production exactly
  - All numbered/bulleted items match production exactly
  - All descriptions and explanations match production exactly

### 5. ⚠️ **COMPONENTES CORRECTOS** - OBLIGATORIO
- ✅ **COMPONENTES OBLIGATORIOS (Siempre deben estar):**
  - `cta_section_19` - Hero CTA (siempre primero)
    - ⚠️ **IMPORTANTE:** Si en producción NO hay botón visible, establecer `button.label: null`
    - ⚠️ **CRÍTICO:** El link del botón DEBE ser exactamente el mismo que en producción (ver sección 1.5)
  - `trust_badges_1` - Trust Badges (siempre segundo, OBLIGATORIO)
    - ⚠️ **CRÍTICO:** DEBE tener la estructura completa con `external_page`:
      ```yaml
      external_page:
        - id: [id]a
          review_pages: trustpilot
          type: new_set
          enabled: true
        - id: [id]b
          review_pages: shopper_approved
          type: new_set
          enabled: true
      ```
  - `info_4` - Join 1M+ Info (siempre tercero, OBLIGATORIO)
    - ⚠️ **CRÍTICO:** DEBE tener la estructura completa con `title`, `description`, `asset`:
      ```yaml
      title:
        - type: paragraph
          content:
            - type: text
              text: 'Join '
            - type: text
              marks:
                - type: textColor
                  attrs:
                    color: '#FF4A00'
              text: '1,000,000+'
            - type: text
              text: ' Entrepreneurs like you'
      description: 'Entrepreneurship is booming – and we're happy to be one of America's fastest growing companies.'
      asset: inc5000_x2.webp
      ```
    - ⚠️ **CRÍTICO:** Copiar EXACTAMENTE la estructura de otros archivos migrados (ej: women-business.md)
- ✅ **COMPONENTES OPCIONALES (Solo si están en producción):**
  - `table_of_contents` - Solo si hay tabla de contenidos en producción
  - `info` (info_28, info_29, info_11) - Solo si hay bloques de info adicionales en producción
  - `faq` - Solo si hay FAQs en producción
  - `disclaimer_text` - Solo si hay disclaimer en producción
  - `video` - Solo si hay videos en producción
  - `text` - Solo si hay texto simple en producción
  - `cta_section` (final) - Solo si hay CTA final en producción
  - `info_group_tabs` - Solo si hay tabs de recursos/herramientas en producción
- ✅ **⚠️ CRÍTICO:** NUNCA agregar componentes que no estén en la página original de producción
- ✅ Verificar que cada componente tenga `type`, `enabled: true` y `id` único

### 6. ⚠️ **CAMPOS SEO** - OBLIGATORIO
- ✅ Extraer de producción:
  - `seo_custom_meta_title`: Del tag `<title>` de producción
  - `seo_custom_meta_description`: De la meta description de producción
  - `seo_title`: title (o custom si aplica)
  - `seo_meta_description`: custom (o empty si aplica)
  - `seo_canonical`: none
  - `seo_og_description`: general
  - `seo_og_title`: title
  - `seo_tw_title`: title
  - `seo_tw_description`: general
- ✅ **NUNCA** omitir campos SEO

### 7. ⚠️ **ESTRUCTURA DE BLOQUES** - OBLIGATORIO
- ✅ Todos los bloques deben tener:
  - `id`: UUID único
  - `type`: Tipo de componente correcto
  - `enabled: true`
  - `version`: Versión correcta del componente
- ✅ Verificar que la estructura de bloques anidados sea correcta
- ✅ Verificar que los bloques estén en el orden correcto

### 8. ⚠️ **COMILLAS DOBLES** - CRÍTICO
- ✅ **SIEMPRE** usar comillas dobles (`"`) para TODOS los strings en YAML
- ✅ **NUNCA** usar comillas simples (`'`) como wrapper principal
- ✅ Si hay comillas dobles dentro del texto, escapar con `\"`
- ✅ **NO escapar comillas simples** cuando usas comillas dobles como wrapper
- ✅ **OBLIGATORIO:** Al finalizar la migración, ejecutar el script `python3 llc-v2-pages-migration/replace_quotes_final.py content/collections/start_a_business_pages/[slug].md` para reemplazar comillas simples por tipográficas en campos de texto

### 9. ⚠️ **AGREGAR A RELEASED PAGES** - CRÍTICO
- ✅ **OBLIGATORIO:** Después de crear el archivo markdown, DEBES agregar la ruta a `app/Routing/migration/released-pages.php`
- ✅ **Formato:** La ruta debe ser `/start-a-business/[slug]` (ejemplo: `/start-a-business/dog-walking-business`)
- ✅ **Ubicación:** Agregar la ruta en orden alfabético o al final del array en `released-pages.php`
- ✅ **⚠️ CRÍTICO:** Si no agregas la página a `released-pages.php`, la página NO será servida por Statamic y seguirá usando PayloadCMS
- ✅ **Verificación:** Después de agregar, verifica que la ruta esté correctamente formateada y sin errores de sintaxis PHP

## ✅ Checklist Final Antes de Completar Migración

Antes de considerar la migración completa, verifica CADA punto:

- [ ] ⚠️ **CRÍTICO:** ¿El UUID de la página es único? (NUNCA copiar UUID de otra página)
- [ ] ⚠️ **OBLIGATORIO:** ¿TODAS las imágenes están subidas a S3 y referenciadas correctamente?
- [ ] ⚠️ **OBLIGATORIO:** ¿Revisaste que TODOS los links del contenido original están incluidos en formato Bard?
- [ ] ⚠️ **CRÍTICO:** ¿El contenido es exactamente igual al de producción? (no inventado, no modificado)
- [ ] ⚠️ **OBLIGATORIO:** ¿Todos los componentes usan el tipo correcto (`cta_section`, `table_of_contents`, `info`, etc.)?
- [ ] ⚠️ **OBLIGATORIO:** ¿Todos los bloques tienen `id`, `type`, `enabled: true` y `version` correctos?
- [ ] ⚠️ **OBLIGATORIO:** ¿Todos los campos SEO están agregados y correctos?
- [ ] ⚠️ **CRÍTICO:** ¿TODOS los strings usan comillas dobles (`"`)? (NUNCA usar comillas simples `'`)
- [ ] ⚠️ **OBLIGATORIO:** ¿Ejecutaste el script `replace_quotes_final.py` para reemplazar comillas simples por tipográficas?
- [ ] ⚠️ **OBLIGATORIO:** ¿Todos los videos de Wistia están incluidos como bloques `video`?
- [ ] ⚠️ **OBLIGATORIO:** ¿La estructura de bloques anidados es correcta?
- [ ] ⚠️ **CRÍTICO:** ¿Agregaste la ruta `/start-a-business/[slug]` a `app/Routing/migration/released-pages.php`?

## 📝 Notas Importantes

- Este checklist debe revisarse **SIEMPRE** antes de completar cualquier migración
- Si falta algún punto, la migración NO está completa
- Es mejor tomar más tiempo verificando que tener que corregir después
- Cuando dudes, consulta la documentación completa en los archivos README correspondientes
- Revisa ejemplos de páginas ya migradas en `content/collections/start_a_business_pages/`
