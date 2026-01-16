# 🎓 Guía de Entrenamiento: Mapeo HTML → Componentes Statamic

Este documento documenta cómo mapear elementos HTML de producción a componentes de Statamic basándose en páginas ya migradas.

## 📋 Páginas de Referencia Analizadas

1. ✅ `with-no-money-business` - https://bizee.com/start-a-business/with-no-money-business
2. ✅ `laundromat-business` - https://bizee.com/start-a-business/laundromat-business
3. ✅ `teen-business` - https://bizee.com/start-a-business/teen-business
4. ✅ `event-planning` - https://bizee.com/start-a-business/event-planning
5. ✅ `bookstore` - https://bizee.com/start-a-business/bookstore
6. ✅ `travel-agency` - https://bizee.com/start-a-business/travel-agency
7. ✅ `shirt-business` - https://bizee.com/start-a-business/shirt-business ⚠️ **MIGRACIÓN MANUAL RECIENTE - REFERENCIA PRINCIPAL**

## 🎯 Aprendizajes Clave de la Migración Manual Reciente (`shirt-business`)

### ⚠️ Correcciones Críticas Identificadas:

1. **`info_11` con `two_columns: true`:**
   - ✅ Usa `type: heading` (level 2) para el título, NO `paragraph`
   - ✅ Los items usan `type: new_item` con `title` y `description` separados
   - ✅ Los items NO tienen `asset` ni iconos
   - ✅ `select_an_icon: false` siempre

2. **`faq_1` vs `faq_2`:**
   - ✅ `faq_1` se usa para listas de pasos/items (ej: "A Plan for Starting Your T-Shirt Business")
   - ✅ `faq_2` se usa para FAQs tradicionales (preguntas y respuestas) - ⚠️ **SIEMPRE usar `faq_2` para FAQs**
   - ⚠️ NO usar `faq_1` para FAQs tradicionales

3. **`text_container` para estadísticas:**
   - ✅ Usar `text_container` con `text_content_version: disclaimer` para estadísticas destacadas (ej: "$3B", "$10B")
   - ✅ Aparece dentro de `table_of_contents` items

4. **`text_with_icon` versiones:**
   - ✅ `version: white` - Para items en fondos claros
   - ✅ `version: gray` - Para otros casos
   - ✅ Siempre incluir el campo `icon`

5. **`disclaimer_text_1`:**
   - ✅ Puede tener headings dentro del contenido usando `type: heading` con `level: 3`
   - ✅ Puede tener múltiples párrafos con diferentes formatos

6. **`info_group_tabs`:**
   - ✅ Puede tener un campo `description` opcional

7. **CTA Final:**
   - ✅ `cta_section_21` es la versión preferida para CTAs finales (ej: "Ready to Start Your T-Shirt Business?")

8. **Separación de items en `table_of_contents`:**
   - ✅ Separar mejor los items usando items individuales de `text_with_icon` en lugar de texto largo
   - ✅ Usar items de `type: text` para títulos de secciones (ej: "### What Are the Main Challenges...")

## ⚠️ COMPONENTES OBLIGATORIOS vs OPCIONALES

### 🔴 Componentes OBLIGATORIOS (Siempre deben estar)

Estos 3 componentes **SIEMPRE** deben estar presentes en cada página migrada, en este orden exacto:

1. **Hero CTA** → `cta_section` (version: `cta_section_19`)
2. **Trust Badges** → `trust_badges` (version: `trust_badges_1`)
3. **Join 1M+ Info** → `info` (version: `info_4`)

**⚠️ CRÍTICO:** Estos son los ÚNICOS componentes obligatorios. Todos los demás componentes son OPCIONALES y solo deben agregarse si están presentes en la página de producción original.

### 🟢 Componentes Opcionales

Todos los demás componentes (table_of_contents, info blocks adicionales, faq, info_group_tabs, CTA final, etc.) son **OPCIONALES** y solo deben agregarse si están presentes en la página de producción original.

**⚠️ REGLA CRÍTICA:** NUNCA agregues componentes que no estén en la página original de producción. Si un componente no está visible en producción, NO lo agregues.

---

## 📝 REGLA CRÍTICA: Agregar a Released Pages

### ⚠️ OBLIGATORIO después de cada migración:

**Después de migrar cualquier página de `start_a_business_pages`, SIEMPRE agregar el link a `app/Routing/migration/released-pages.php`**

**Formato del link:**
```php
'/start-a-business/[slug-de-la-pagina]',
```

**Ejemplo:**
- Archivo migrado: `content/collections/start_a_business_pages/etsy-business.md`
- Link a agregar: `'/start-a-business/etsy-business',`

**Ubicación en el archivo:**
- Agregar después de las otras páginas de `start-a-business` (alrededor de la línea 132)
- Mantener orden alfabético o cronológico según el patrón existente

**⚠️ NO OLVIDAR:** Esta es una regla obligatoria. Cada migración debe incluir este paso.

---

## 🧩 Patrones de Mapeo Identificados

### 1. Hero Section / CTA Principal → `cta_section` (version: `cta_section_19`) ⚠️ OBLIGATORIO

**Patrón en Producción:**
- Sección hero al inicio de la página
- Típicamente contiene:
  - Un `<h1>` grande con el título principal
  - Un párrafo descriptivo
  - ⚠️ **IMPORTANTE:** Verificar si hay botón visible. Si NO hay botón en producción, establecer `button.label: null`
  - Un botón CTA principal (ej: "Start Now", "Form your business", etc.)

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  version: cta_section_19
  # ⚠️ Si NO hay botón en producción, usar: button.label: null
  title:
    -
      type: heading
      attrs:
        level: 1
      content:
        -
          type: text
          marks:
            -
              type: bold
          text: 'Título del H1'
  description:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Descripción del párrafo'
  images:
    image: null
    mobile_image: null
  button:
    label: 'Texto del botón'
    link: 'URL del botón'
    target_blank: false
    variant: primary
    alignment: mx-auto
    # ... otros campos
  type: cta_section
  enabled: true
```

**Ejemplos encontrados:**
- `travel-agency`: "How to Start a Travel Agency" con botón "Start Now"
- `bookstore`: "How to Open an Independent Bookstore Business" con botón "Start now"
- `teen-business`: "How to Start a Teen Business" con botón "Start Your Teen Business"

---

### 2. Trust Badges → `trust_badges` (version: `trust_badges_1`)

**Patrón en Producción:**
- Sección con badges de Trustpilot y Shopper Approved
- Aparece justo después del hero/CTA principal

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  external_page:
    -
      id: [UUID único]
      review_pages: trustpilot
      type: new_set
      enabled: true
    -
      id: [UUID único]
      review_pages: shopper_approved
      type: new_set
      enabled: true
  version: trust_badges_1
  type: trust_badges
  enabled: true
```

**Nota:** Este componente es **OBLIGATORIO** y aparece en TODAS las páginas migradas, siempre después del hero.

---

### 3. Info Block "Join 1,000,000+ Entrepreneurs" → `info` (version: `info_4`) ⚠️ OBLIGATORIO

**Patrón en Producción:**
- Sección con el mensaje "Join 1,000,000+ Entrepreneurs like you"
- Incluye imagen del logo INC5000
- Aparece después de trust badges

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  version: info_4
  title:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Join '
        -
          type: text
          marks:
            -
              type: textColor
              attrs:
                color: '#FF4A00'
          text: '1,000,000+'
        -
          type: text
          text: ' Entrepreneurs like you'
  description: "Entrepreneurship is booming – and we're happy to be one of America's fastest growing companies."
  type: info
  enabled: true
  order_reverse: false
  show_display_options: false
  asset: inc5000_x2.webp
  show_index: true
```

**Nota:** Este bloque es **OBLIGATORIO** y aparece en TODAS las páginas migradas.

---

### 4. Tabla de Contenido Principal → `table_of_contents` (version: `table_of_contents_v1`)

**Patrón en Producción:**
- Sección principal con el contenido estructurado
- Título como "On this page" o similar
- Múltiples secciones (`toc_items`) con:
  - Títulos (H2, H3, H4)
  - Items que pueden contener:
    - Texto simple
    - Texto con iconos (`text_with_icon`)
    - Videos (`video`)
    - Imágenes (`image_with_description`)
    - Botones (`simple_button`, `article_button`)
    - Contenedores de texto (`text_container`)
    - FAQs anidados (`faq`)

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  version: table_of_contents_v1
  title:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'On this page'  # o "Starting a [Business] Business"
  toc_items:
    -
      id: [UUID único]
      title:
        -
          type: heading
          attrs:
            level: 2  # o 3, 4
          content:
            -
              type: text
              marks:
                -
                  type: bold
              text: 'Título de la sección'
      items:
        # Array de items aquí
      type: toc_items
      enabled: true
  type: table_of_contents
  enabled: true
```

**Tipos de Items dentro de `toc_items`:**

#### a) Texto Simple (`type: text`)
```yaml
-
  id: [UUID único]
  text: 'Texto con <br> para saltos de línea'
  type: text
  enabled: true
```

#### b) Texto con Icono (`type: text_with_icon`)
```yaml
-
  id: [UUID único]
  version: gray  # o white - ⚠️ IMPORTANTE: Usar 'white' para items en fondos claros, 'gray' para otros casos
  text: |-
    **Texto en negrita**
    Descripción del texto aquí
  icon: checkbox-circle-duocolor.svg  # ⚠️ IMPORTANTE: Siempre incluir el icon
  type: text_with_icon
  enabled: true
```

**⚠️ NOTA IMPORTANTE sobre versiones:**
- `version: white` - Usar cuando el item está en un fondo claro/claro
- `version: gray` - Usar en otros casos (fondo gris o cuando se necesita más contraste)

**Ejemplos:**
- `shirt-business`: Items con estadísticas usan `version: white`
- `shirt-business`: Items de "Benefits and Challenges" usan `version: gray`

#### c) Video (`type: video`)
```yaml
-
  id: [UUID único]
  version: video_1
  video_url: 'https://incfile.wistia.com/medias/[VIDEO_ID]'  # o YouTube
  show_video_object: false
  type: video
  enabled: true
```

#### d) Contenedor de Texto (`type: text_container`) - Para Estadísticas Destacadas
```yaml
-
  id: [UUID único]
  text_content_version: disclaimer
  text: |-
    **$3B**
    Total revenue for online T-shirt printing and sales a year
  type: text_container
  enabled: true
```

**⚠️ IMPORTANTE:** `text_container` se usa específicamente para estadísticas o números destacados que aparecen dentro de `table_of_contents`. Siempre incluir `text_content_version: disclaimer`.

**Ejemplos encontrados:**
- `shirt-business`: "$3B" y "$10B" en estadísticas del mercado
- `travel-agency`: Estadísticas similares del mercado de viajes
- `bookstore`: Estadísticas del mercado de librerías

#### e) Botón Simple (`type: simple_button`)
```yaml
-
  id: [UUID único]
  toc_label: 'Texto del botón'
  toc_link: 'https://...'
  toc_target_blank: false
  toc_variant: primary
  type: simple_button
  enabled: true
```

#### f) Botón Article (`type: article_button`)
```yaml
-
  id: [UUID único]
  toc_version: article_button_1
  toc_label:
    -
      type: paragraph
      attrs:
        textAlign: left
      content:
        -
          type: text
          text: 'Texto del botón'
  toc_url: 'https://...'
  toc_open_in_new_tab: false
  type: article_button
  enabled: true
```

**Ejemplos encontrados:**
- `with-no-money-business`: "Learn how to start a business in your state" button dentro de `table_of_contents`

**Nota:** Este botón se usa específicamente para promocionar artículos o recursos relacionados dentro de la tabla de contenidos.

#### g) FAQ Anidado (`type: faq`)
```yaml
-
  id: [UUID único]
  toc_version: faq_1
  toc_seo_data_type: faq
  toc_list_mode: always
  toc_items:
    -
      id: [UUID único]
      title:
        -
          type: paragraph
          content:
            -
              type: text
              text: 'Pregunta'
      description: 'Respuesta'
      type: new_item
      enabled: true
  toc_toggle: false
  toc_alignment: default
  toc_button_width: w-full
  toc_capitalized: false
  type: faq
  enabled: true
```

#### h) Card (`type: card`)
```yaml
-
  id: [UUID único]
  text:
    -
      type: paragraph
      content:
        -
          type: text
          marks:
            -
              type: bold
          text: 'Título de la card'
    -
      type: paragraph
      content:
        -
          type: text
          marks:
            -
              type: small
          text: 'Subtítulo'
  asset: imagen.webp
  label: 'Texto del botón'
  link: 'https://...'
  variant: primary
  type: card
  enabled: true
```

---

### 5. Bloques de Información con Items → `info` (version: `info_28`, `info_29`, `info_11`)

**Patrón en Producción:**
- Secciones con título H2 o título simple
- Descripción opcional
- Lista de items con:
  - Título (en negrita)
  - Descripción
  - Icono opcional
- Puede tener layout de dos columnas (`info_11` con `two_columns: true`)

**Mapeo a Statamic:**

**Para `info_28` y `info_29`:**
```yaml
-
  id: [UUID único]
  version: info_28  # o info_29
  title:
    -
      type: heading
      attrs:
        level: 2
      content:
        -
          type: text
          marks:
            -
              type: bold
          text: 'Título H2'
  description: 'Descripción opcional'
  order_reverse: false
  show_index: true
  items:
    -
      id: [UUID único]
      title:
        -
          type: paragraph
          content:
            -
              type: text
              marks:
                -
                  type: bold
              text: 'Título del item'
      description: 'Descripción del item'
      asset: icono.svg  # opcional
      select_an_icon: false
      type: new_item
      enabled: true
  background: '#fafafa'  # opcional
  type: info
  enabled: true
```

**Para `info_11` (con layout de dos columnas):**
```yaml
-
  id: [UUID único]
  version: info_11
  title:
    -
      type: heading  # ⚠️ CORRECCIÓN: info_11 usa heading (level 2), no paragraph
      attrs:
        level: 2
      content:
        -
          type: text
          marks:
            -
              type: bold
          text: 'Título de la sección'
  description: 'Descripción opcional'
  order_reverse: false
  show_index: true
  show_display_options: false
  items:
    -
      id: [UUID único]
      title:
        -
          type: paragraph
          content:
            -
              type: text
              marks:
                -
                  type: bold
              text: 'Título del item'
      description: 'Descripción del item'
      select_an_icon: false  # ⚠️ IMPORTANTE: Siempre false para info_11
      type: new_item
      enabled: true
      # ⚠️ NOTA: NO usar asset aquí, info_11 NO usa iconos en los items
  two_columns: true  # ⚠️ IMPORTANTE: Solo para info_11
  type: info
  enabled: true
```

**Diferencias entre versiones:**
- **`info_28`**: Usa `heading` (level 2) para el título, layout estándar de una columna, items con `asset` e iconos
- **`info_29`**: Similar a `info_28`, usado para listas de items sin iconos (ej: "Write a Business Plan")
- **`info_11`**: ⚠️ **CORRECCIÓN:** Usa `heading` (level 2) para el título, NO `paragraph`. Puede tener `two_columns: true` para layout de dos columnas. Items NO tienen `asset` ni iconos, solo `title` y `description`

**Ejemplos encontrados:**
- `shirt-business`: "Do Market Research and Validate Your T-Shirt Products and Services" (info_11 con `two_columns: true`) ⚠️ **EJEMPLO RECIENTE**
- `travel-agency`: "What Type of Travel Agency Business Should You Start?" (info_28)
- `bookstore`: "Validating Your Bookstore Idea" (info_28)
- `bookstore`: "Maintaining Your Bookstore Business" (info_11)
- `blockchain`: "How to Move Your Business to the Blockchain" (info_11 con `two_columns: true`)
- `event-planning`: "3. Create a Business Plan" (dentro de table_of_contents)

**⚠️ NOTA CRÍTICA sobre `info_11`:**
- Los items en `info_11` NO deben tener `asset` ni iconos
- Los items deben usar `type: new_item` con `title` (paragraph con texto en bold) y `description` (texto plano)
- `select_an_icon: false` siempre
- El título del componente usa `type: heading` con `level: 2`, NO `paragraph`

---

### 6. FAQs → `faq` (version: `faq_2`) ⚠️ CORRECCIÓN IMPORTANTE

**Patrón en Producción:**
- Sección de preguntas frecuentes
- Título de la sección
- Lista de preguntas y respuestas

**⚠️ CRÍTICO:** Las FAQs tradicionales **SIEMPRE** usan `version: faq_2`, NO `faq_1`

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  version: faq_2  # ⚠️ SIEMPRE faq_2 para FAQs tradicionales
  seo_data_type: faq
  title:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Título de la sección FAQ'
  description: 'Descripción opcional'
  list_mode: always
  items:
    -
      id: [UUID único]
      title:
        -
          type: paragraph
          content:
            -
              type: text
              text: 'Pregunta'
      description: 'Respuesta'
      select_an_icon: false
      type: new_item
      enabled: true
  toggle: false
  alignment: default
  button_width: w-full
  capitalized: false
  type: faq
  enabled: true
```

**Ejemplos encontrados:**
- `laundromat-business`: "Laundromat FAQs" (faq_2)
- `event-planning`: "Common Questions About Event Planning" (faq_2)

---

### 6b. Listas de Pasos → `faq` (version: `faq_1`)

**Patrón en Producción:**
- Lista de pasos/items con títulos y descripciones
- NO es una FAQ tradicional, pero usa el componente `faq` con `version: faq_1`

**⚠️ IMPORTANTE:** `faq_1` se usa SOLO para listas de pasos/items, NO para FAQs tradicionales

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  version: faq_1  # ⚠️ Solo para listas de pasos, NO FAQs
  seo_data_type: faq
  title:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Título de la Lista de Pasos'
  description: |-
    Descripción opcional.
    Puede incluir <br><br> para saltos de línea.
    <br><br>Aquí más texto si es necesario.
  list_mode: always
  items:
    -
      id: [UUID único]
      title:
        -
          type: paragraph
          content:
            -
              type: text
              text: 'Título del Paso'
      description: 'Descripción del paso'
      select_an_icon: false
      type: new_item
      enabled: true
  toggle: false
  alignment: default
  button_width: w-full
  capitalized: false
  type: faq
  enabled: true
```

**Ejemplos encontrados:**
- `shirt-business`: "A Plan for Starting Your T-Shirt Business" (faq_1 - lista de pasos)

---

### 7. Info Group Tabs → `info_group_tabs` (version: `info_group_tabs_v8`)

**Patrón en Producción:**
- Sección con múltiples tabs
- Cada tab contiene una lista de herramientas/recursos
- Típicamente aparece al final de la página

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  version: info_group_tabs_v8
  title:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Título de la sección'
  description: 'Descripción opcional'  # ⚠️ IMPORTANTE: Este campo puede estar presente
  badge_version: badge_primary
  show_icon_in_mobile: false
  tab_group:
    -
      id: [UUID único]
      version: tab_group_v1
      toggle: false
      label: 'Nombre del Tab'  # ⚠️ IMPORTANTE: Usar `label:` (texto directo), NO `title:` con paragraph
      alignment: default  # ⚠️ OBLIGATORIO
      button_width: w-full  # ⚠️ OBLIGATORIO
      capitalized: false
      tab_items:
        -
          id: [UUID único]
          title:
            -
              type: paragraph
              content:
                -
                  type: text
                  text: 'Nombre de la herramienta'
          link: 'https://...'
          type: new_set
          enabled: true
      type: new_set  # ⚠️ IMPORTANTE: Usar `new_set`, NO `tab_group`
      enabled: true
  type: info_group_tabs
  enabled: true
```

**⚠️ REGLAS CRÍTICAS para `tab_group_v1`:**
1. ✅ Usar `label:` (texto directo) para el nombre del tab, NO `title:` con estructura de paragraph
2. ✅ Incluir `alignment: default` (obligatorio)
3. ✅ Incluir `button_width: w-full` (obligatorio)
4. ✅ Usar `type: new_set` al final del tab_group, NO `type: tab_group`

**Ejemplos encontrados:**
- `travel-agency`: "Useful Online Tools for Your Travel Agency"
- `bookstore`: "Useful Online Tools for Your Bookstore Business"
- `teen-business`: "Useful Online Tools for Your Landscaping Business" (parece ser un copy-paste error, pero muestra el patrón)

---

### 8. Text Container → `text_container` (para estadísticas destacadas)

**Patrón en Producción:**
- Estadísticas o números destacados (ej: "$3B", "$10B")
- Aparece dentro de `table_of_contents` items

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  text_content_version: disclaimer
  text: |-
    **$3B**
    Total revenue for online T-shirt printing and sales a year
  type: text_container
  enabled: true
```

**Ejemplos encontrados:**
- `shirt-business`: "$3B" y "$10B" en estadísticas
- `travel-agency`: Estadísticas similares
- `bookstore`: Estadísticas del mercado

---

### 9. Disclaimer Text → `disclaimer_text` (version: `disclaimer_text_1`)

**Patrón en Producción:**
- Texto de disclaimer/disclaimer
- Puede aparecer en diferentes partes de la página
- Puede contener headings, párrafos y links

**⚠️ IMPORTANTE:** El disclaimer puede tener headings dentro del contenido usando `type: heading` con `level: 3`

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  version: disclaimer_text_1
  content:
    -
      type: heading  # ⚠️ Puede tener headings dentro
      attrs:
        textAlign: left
        level: 3
      content:
        -
          type: text
          marks:
            -
              type: bold
          text: 'Título del Disclaimer'
    -
      type: paragraph
      attrs:
        textAlign: left
      content:
        -
          type: text
          marks:
            -
              type: bold
          text: 'Texto del disclaimer en negrita'
    -
      type: paragraph
      attrs:
        textAlign: left
      content:
        -
          type: text
          text: 'Texto normal del disclaimer'
        -
          type: text
          marks:
            -
              type: link
              attrs:
                href: 'https://...'
                rel: null
                target: null
                title: null
          text: 'link text'
  type: disclaimer_text
  enabled: true
```

**Ejemplos encontrados:**
- `bookstore`: "Please note: This page contains affiliate links..."
- `teen-business`: Disclaimer sobre affiliate links
- `with-no-money-business`: Varios disclaimers

---

### 9. CTA Final → `cta_section` (version: `cta_section_9`, `cta_section_21`, `cta_section_2`)

**Patrón en Producción:**
- Sección CTA al final de la página
- Típicamente con título, descripción y botón
- Puede incluir imagen
- **⚠️ OPCIONAL:** Solo agregar si está presente en producción

**Mapeo a Statamic:**

**Para `cta_section_2` (versión simple):**
```yaml
-
  id: [UUID único]
  version: cta_section_2
  title:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Launch your business with bizee'
  description:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'No Contracts. No Surprises. Only $0 + State Fee to Launch Your Business.'
  images:
    image: null
    mobile_image: null
  button:
    label: 'Start your [Business Type] Business with us, today!'
    link: 'https://orders.bizee.com/form-order-now.php?utm_campaign={campaign}&utm_source=adwords&utm_medium=ppc&'
    target_blank: false
    custom_alignment: false
    variant: primary
    # ... otros campos
  type: cta_section
  enabled: true
```

**Para `cta_section_9` (con imagen):**
```yaml
-
  id: [UUID único]
  version: cta_section_9
  title:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Título del CTA'
  description:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Descripción'
  images:
    image: imagen.webp  # opcional
    mobile_image: null
  button:
    label: 'Texto del botón'
    link: 'https://...'
    variant: primary
    # ... otros campos
  type: cta_section
  enabled: true
```

**Para `cta_section_21` (versión completa):**
```yaml
-
  id: [UUID único]
  version: cta_section_21
  title:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Título del CTA'
  description:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Descripción'
  images:
    image: null
    mobile_image: null
  button:
    label: 'Texto del botón'
    link: 'https://...'
    variant: primary
    # ... otros campos
  type: cta_section
  enabled: true
```

**Diferencias entre versiones:**
- **`cta_section_2`**: Versión más simple, sin campos adicionales de selector, típicamente usado para CTAs finales estándar
- **`cta_section_9`**: Puede incluir imagen, usado para CTAs con contenido visual (ej: "The Complete 'Start Your Business' Checklist")
- **`cta_section_21`**: ⚠️ **VERSIÓN PREFERIDA** para CTAs finales según migración manual reciente. Versión más completa con campos adicionales de selector

**⚠️ IMPORTANTE:** Según la migración manual reciente de `shirt-business`, `cta_section_21` es la versión preferida para CTAs finales (ej: "Ready to Start Your T-Shirt Business?").

**Ejemplos encontrados:**
- `shirt-business`: "Ready to Start Your T-Shirt Business?" (cta_section_21) ⚠️ **EJEMPLO RECIENTE**
- `bookstore`: "The Complete 'Start Your Business' Checklist" (cta_section_9)
- `travel-agency`: "Conclusion" (cta_section_21)
- `laundromat-business`: "Change the Laundry Game" (cta_section_2)
- `blockchain`: "Launch your business with bizee" (cta_section_2)

---

### 10. Blocks Container → `blocks`

**Patrón en Producción:**
- Contenedor que agrupa múltiples componentes relacionados
- Se usa para agrupar secciones que están relacionadas temáticamente
- Puede contener `table_of_contents`, `info`, `cta_section`, etc.

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  blocks:
    -
      id: [UUID único]
      version: table_of_contents_v1  # o info_28, cta_section_9, etc.
      # ... estructura del componente
      type: table_of_contents  # o info, cta_section, etc.
      enabled: true
    # ... más componentes
  type: blocks
  enabled: true
```

**Ejemplos encontrados:**
- `travel-agency`: Contenedor con `table_of_contents` anidado ("Right Business Structure")
- `travel-agency`: Contenedor con múltiples `info` blocks ("Insurance", "Maintaining")

**Nota:** Los `blocks` pueden anidarse dentro de otros `blocks` o aparecer directamente en el `blocks` principal de la página.

---

### 11. Components Container → `components`

**Patrón en Producción:**
- Contenedor específico para componentes de texto (`text`)
- Se usa para agrupar contenido de texto con headings y párrafos
- Aparece en secciones específicas que requieren solo texto

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  component:
    -
      id: [UUID único]
      text:
        -
          type: heading
          attrs:
            textAlign: left
            level: 2  # o 3
          content:
            -
              type: text
              marks:
                -
                  type: bold
              text: 'Título'
        -
          type: paragraph
          attrs:
            textAlign: left
          content:
            -
              type: text
              text: 'Contenido del párrafo'
            -
              type: text
              marks:
                -
                  type: link
                  attrs:
                    href: 'https://...'
                    rel: null
                    target: null
                    title: null
              text: 'link text'
      bard_alignment: false
      secondary_font: false
      custom_font_size: false
      show_display_options: false
      type: text
      enabled: true
  type: components
  enabled: true
```

**Ejemplos encontrados:**
- `travel-agency`: "Rules, Regulations and Taxes for Your Travel Agency"

**Nota:** El componente `text` dentro de `components` puede contener múltiples headings y párrafos con links.

---

### 12. Card Component → `card` (dentro de `table_of_contents`)

**Patrón en Producción:**
- Card promocional dentro de la tabla de contenidos
- Típicamente promociona recursos como "Business Checklist", guías, etc.
- Incluye imagen, título, subtítulo y botón

**Mapeo a Statamic:**
```yaml
-
  id: [UUID único]
  text:
    -
      type: paragraph
      attrs:
        textAlign: left
      content:
        -
          type: text
          marks:
            -
              type: bold
          text: 'Título de la Card'
    -
      type: paragraph
      attrs:
        textAlign: left
      content:
        -
          type: text
          marks:
            -
              type: small
          text: 'Subtítulo o descripción'
  bard_alignment: false
  secondary_font: false
  custom_font_size: false
  asset: imagen.webp  # Imagen de la card
  label: 'Texto del botón'
  link: 'https://...'  # URL del recurso
  target_blank: false
  custom_size: false
  custom_alignment: false
  capitalized: false
  custom_icon: false
  variant: primary
  custom_text_align: false
  type: card
  enabled: true
```

**Ejemplos encontrados:**
- `teen-business`: "The Complete 'Start Your Business' Checklist" card dentro de `table_of_contents`

**Nota:** Las cards aparecen dentro de `toc_items.items` dentro de `table_of_contents`.

---

## 📊 Orden Típico de Componentes

Basado en las páginas analizadas, el orden típico es:

1. **Hero/CTA Principal** → `cta_section` (version: `cta_section_19`)
2. **Trust Badges** → `trust_badges` (version: `trust_badges_1`)
3. **Info "Join 1M+"** → `info` (version: `info_4`)
4. **Tabla de Contenido Principal** → `table_of_contents` (version: `table_of_contents_v1`)
   - Contiene múltiples `toc_items` con diferentes tipos de items
5. **FAQs** → `faq` (version: `faq_1`) - Opcional
6. **Info Group Tabs** → `info_group_tabs` (version: `info_group_tabs_v8`) - Opcional
7. **Disclaimer** → `disclaimer_text` (version: `disclaimer_text_1`) - Opcional
8. **CTA Final** → `cta_section` (version: `cta_section_9`, `cta_section_21`, etc.)

---

## 🔍 Cómo Identificar Componentes en HTML de Producción

### Para `cta_section`:
- Buscar secciones con `<h1>` grande al inicio de la página
- Buscar secciones con botones prominentes (botones primarios grandes)
- Buscar estructura: H1 + párrafo descriptivo + botón
- **Ejemplo de producción:** Hero section con título principal y botón "Start Now"

### Para `trust_badges`:
- Aparece SIEMPRE después del hero/CTA principal
- Contiene badges de Trustpilot y Shopper Approved
- **Nota:** Este componente es estándar y aparece en TODAS las páginas

### Para `info` (versión `info_4` - "Join 1M+"):
- Aparece SIEMPRE después de trust badges
- Contiene el mensaje "Join 1,000,000+ Entrepreneurs like you"
- Incluye imagen del logo INC5000
- **Nota:** Este componente es estándar y aparece en TODAS las páginas

### Para `table_of_contents`:
- Sección principal con el contenido estructurado
- Título como "On this page" o "Starting a [Business] Business"
- Contiene múltiples secciones (`toc_items`) con:
  - Títulos H2, H3, H4
  - Items que pueden ser: texto, videos, imágenes, botones, FAQs anidados
- **Patrón:** Es la sección más grande y compleja de la página

### Para `info` (versiones `info_28`, `info_29`, `info_11`):
- Secciones con título H2
- Descripción opcional
- Lista de items con título (negrita) y descripción
- Puede tener iconos (`asset`)
- Puede tener fondo de color (`background: '#fafafa'` o `'#ffffff'`)
- **Ejemplos:** "What Type of Travel Agency Business Should You Start?", "Validating Your Bookstore Idea"

### Para `faq`:
- Sección independiente con título de FAQ
- Lista de preguntas y respuestas
- Puede aparecer también dentro de `table_of_contents` como FAQ anidado
- **Ejemplos:** "Laundromat FAQs", "Common Questions About Event Planning"

### Para `info_group_tabs`:
- Sección con múltiples tabs/pestañas
- Cada tab contiene una lista de herramientas/recursos
- Típicamente aparece al final de la página
- **Ejemplo:** "Useful Online Tools for Your Travel Agency" con tabs de Project Management, Productivity, etc.

### Para `disclaimer_text`:
- Texto de disclaimer/disclaimer
- Típicamente aparece al final de la página
- Puede contener links
- **Ejemplo:** "Please note: This page contains affiliate links..."

### Para `cta_section` (final):
- Sección CTA al final de la página
- Puede tener diferentes versiones: `cta_section_9`, `cta_section_21`, `cta_section_2`
- Puede incluir imagen
- **Ejemplos:** "The Complete 'Start Your Business' Checklist", "Conclusion", "Form Your Business Today For $0"

### Para `blocks`:
- Contenedor que agrupa múltiples componentes relacionados
- Se usa para agrupar secciones temáticamente relacionadas
- Puede contener `table_of_contents`, `info`, `cta_section`, etc.
- Puede aparecer directamente en el `blocks` principal o anidado dentro de otros `blocks`
- **Ejemplo:** `travel-agency` tiene `blocks` que contienen `table_of_contents` anidado y múltiples `info` blocks

### Para `components`:
- Contenedor específico para componentes de texto (`text`)
- Se usa para agrupar contenido de texto con headings y párrafos
- Aparece en secciones específicas que requieren solo texto
- **Ejemplo:** `travel-agency` tiene `components` con contenido de texto sobre "Rules, Regulations and Taxes"

### Para `card`:
- Card promocional dentro de `table_of_contents`
- Típicamente promociona recursos como "Business Checklist", guías, etc.
- Incluye imagen, título (bold), subtítulo (small), y botón
- Aparece dentro de `toc_items.items` dentro de `table_of_contents`
- **Ejemplo:** `teen-business` tiene una card promocionando "The Complete 'Start Your Business' Checklist"

### Para `article_button`:
- Botón específico para promocionar artículos o recursos relacionados
- Aparece dentro de `toc_items.items` dentro de `table_of_contents`
- Tiene `toc_version: article_button_1` y campos `toc_label`, `toc_url`, `toc_open_in_new_tab`
- **Ejemplo:** `with-no-money-business` tiene un `article_button` para "Learn how to start a business in your state"

---

## ⚠️ Notas Importantes

1. **IDs Únicos:** Cada bloque, item, tab, etc. debe tener su propio UUID único
2. **Versiones:** Usar las versiones correctas según el contexto
3. **Formato Bard:** El contenido de texto usa formato Bard de Statamic
4. **Comillas Dobles:** Siempre usar comillas dobles (`"`) para strings en YAML
5. **Links:** Los links deben estar en formato Bard con `marks` y `attrs`
6. **Videos:** Pueden ser de Wistia (`incfile.wistia.com/medias/`) o YouTube (`youtube.com/embed/`)

---

## 📊 Ejemplos Específicos por Página

### `travel-agency.md`
**Componentes encontrados:**
1. `cta_section_19` - Hero: "How to Start a Travel Agency"
2. `trust_badges_1` - Trust badges
3. `info_4` - "Join 1M+ Entrepreneurs"
4. `table_of_contents_v1` - Contenido principal con múltiples secciones
5. `info_28` - "What Type of Travel Agency Business Should You Start?" (con items)
6. `info_28` - "Validating Your Travel Agency Business Idea" (con items)
7. `info_29` - "Your Travel Agency Needs a Business Plan" (con items)
8. `disclaimer_text_1` - Disclaimer sobre business plan guide
9. `blocks` - Contenedor con `table_of_contents` anidado ("Right Business Structure")
10. `info_28` - "Setting up Your Travel Agency Business Operations" (dentro de blocks)
11. `components` - Contenedor con `text` ("Rules, Regulations and Taxes")
12. `blocks` - Contenedor con `info` ("Insurance for Your Travel Agency", "Maintaining Your Travel Agency Business")
13. `info_group_tabs_v8` - "Useful Online Tools for Your Travel Agency" (con múltiples tabs)
14. `disclaimer_text_1` - Disclaimer sobre affiliate links
15. `cta_section_21` - CTA final "Conclusion"

**Patrones identificados:**
- Usa `blocks` como contenedor para agrupar componentes relacionados
- Usa `components` como contenedor para componentes de texto
- Los `info` blocks pueden aparecer dentro de `blocks` o directamente en `blocks` principal
- Tiene múltiples `info_group_tabs` con muchos tabs (Project Management, Productivity, etc.)

### `bookstore.md`
**Componentes encontrados:**
1. `cta_section_19` - Hero: "How to Open an Independent Bookstore Business"
2. `trust_badges_1` - Trust badges
3. `info_4` - "Join 1M+ Entrepreneurs"
4. `table_of_contents_v1` - Contenido principal con múltiples secciones
5. `info_28` - "Validating Your Bookstore Idea" (con items)
6. `disclaimer_text_1` - Disclaimer sobre validación
7. `info_29` - "Your Bookstore Needs a Business Plan" (con items)
8. `disclaimer_text_1` - Disclaimer sobre business plan guide
9. `table_of_contents_v1` - "Right Business Structure" (segunda tabla de contenido independiente)
10. `info_28` - "Setting up Your Bookstore Business Operations" (con items)
11. `cta_section_9` - "The Complete 'Start Your Business' Checklist"
12. `disclaimer_text_1` - "Rules, Regulations and Taxes"
13. `info_28` - "Insurance for Your Bookstore" (con items)
14. `info_11` - "Maintaining Your Bookstore Business" (con items)
15. `info_group_tabs_v8` - "Useful Online Tools for Your Bookstore Business" (con múltiples tabs)
16. `disclaimer_text_1` - Disclaimer sobre affiliate links
17. `cta_section_21` - CTA final "Conclusion"

**Patrones identificados:**
- Puede tener múltiples `table_of_contents` independientes en la misma página (no anidados)
- Los `disclaimer_text` pueden aparecer en diferentes posiciones (entre componentes)
- Los `info` blocks pueden tener diferentes versiones (`info_28`, `info_11`)
- Estructura más compleja con más componentes que otras páginas

### `with-no-money-business.md`
**Componentes encontrados:**
1. `cta_section_19` - Hero: "How to Start a Business with Little or No Money"
2. `trust_badges_1` - Trust badges
3. `info_4` - "Join 1M+ Entrepreneurs"
4. `table_of_contents_v1` - Contenido principal con:
   - Videos de YouTube
   - Texto con iconos (`text_with_icon`)
   - Contenedores de texto (`text_container`)
   - FAQs anidados (`faq` dentro de `toc_items`)
   - Botones (`article_button`)
5. `faq_1` - "Common Questions About Starting a Business with No Money"
6. `cta_section_9` - "Form Your Business Today For $0"

**Patrones identificados:**
- Los videos pueden ser de YouTube (`youtube.com/embed/`) no solo Wistia
- Los FAQs pueden aparecer dentro de `table_of_contents` como items anidados
- Los `text_with_icon` se usan frecuentemente para listas con iconos

### `laundromat-business.md`
**Componentes encontrados:**
1. `cta_section_19` - Hero: "How to Start a Laundromat Business in 6 Steps"
2. `trust_badges_1` - Trust badges
3. `info_4` - "Join 1M+ Entrepreneurs"
4. `table_of_contents_v1` - Contenido principal con:
   - Videos de YouTube (`video` con `version: video_1`)
   - Texto con iconos (`text_with_icon` con `version: gray` y `version: white`)
   - Contenedores de texto (`text_container` con `text_content_version: disclaimer`)
   - Texto simple (`type: text`)
5. `faq_1` - "Laundromat FAQs" (con múltiples items)
6. `cta_section_2` - CTA final "Change the Laundry Game"

**Patrones identificados:**
- Estructura más simple que otras páginas
- Menos componentes anidados
- Usa `cta_section_2` para el CTA final (versión diferente a otras páginas)

### `blockchain.md` ⚠️ EJEMPLO DE MIGRACIÓN CORRECTA

**Componentes encontrados:**
1. `cta_section_19` - Hero: "How to Make Your Business a Blockchain Startup" ⚠️ OBLIGATORIO
2. `trust_badges_1` - Trust badges ⚠️ OBLIGATORIO
3. `info_4` - "Join 1M+ Entrepreneurs" ⚠️ OBLIGATORIO
4. `table_of_contents_v1` - "On this page" con múltiples secciones (Blockchain Technology, Why You Should Consider, Advantages/Disadvantages)
5. `info_28` - "Business Industries Ideally Suited to Blockchain Applications" (con items y background)
6. `info_11` - "How to Move Your Business to the Blockchain" (con `two_columns: true`)
7. `cta_section_2` - CTA final "Launch your business with bizee"

**⚠️ IMPORTANTE - Componentes NO incluidos:**
- **NO** incluye `info_group_tabs` con Entity Types/Resources/Services/Quick Links (este componente NO estaba en la página original de producción)
- **NO** incluye componentes adicionales que no estaban visibles en producción

**Patrones identificados:**
- ✅ Solo incluye componentes que están presentes en la página original de producción
- ✅ Usa `info_11` con `two_columns: true` para layout de dos columnas
- ✅ Usa `cta_section_2` para el CTA final (versión simple)
- ✅ El `table_of_contents` contiene múltiples secciones con video, texto, text_with_icon, e image_with_description
- ✅ Los items de `info_11` usan `checkbox-circle-blue-duocolor.svg` como icono

**⚠️ LECCIÓN CRÍTICA:** Esta migración es un ejemplo perfecto de cómo NO agregar componentes que no están en producción. Solo se incluyen los componentes que realmente están presentes en la página original.

### `teen-business.md`
**Componentes encontrados:**
1. `cta_section_19` - Hero: "How to Start a Teen Business"
2. `trust_badges_1` - Trust badges
3. `info_4` - "Join 1M+ Entrepreneurs"
4. `table_of_contents_v1` - Contenido principal con:
   - Videos de YouTube (`video` con `version: video_1`)
   - Texto con iconos (`text_with_icon` con `version: gray` y `version: white`)
   - Cards (`type: card`) - "The Complete 'Start Your Business' Checklist"
   - Texto simple (`type: text`)
5. `info_group_tabs_v8` - "Useful Online Tools for Your Landscaping Business" (con múltiples tabs)
6. `disclaimer_text_1` - Disclaimer final sobre affiliate links
7. `cta_section_9` - CTA final "From TikTok to Second-Hand Shop"

**Patrones identificados:**
- Usa `card` dentro de `table_of_contents` para promocionar recursos (única página con este componente)
- Los `text_with_icon` pueden tener diferentes versiones (`gray`, `white`)
- Estructura compleja con muchos `text_with_icon` items

### `event-planning.md`
**Componentes encontrados:**
1. `cta_section_19` - Hero: "How to Start an Event Planning Business"
2. `trust_badges_1` - Trust badges
3. `info_4` - "Join 1M+ Entrepreneurs"
4. `table_of_contents_v1` - Contenido principal con:
   - Contenedores de texto (`text_container` con `text_content_version: disclaimer`)
   - Texto con iconos (`text_with_icon` con `version: gray` y `version: white`)
   - Texto simple (`type: text`)
5. `faq_1` - "Common Questions About Event Planning" (con múltiples items)
6. `cta_section_19` - CTA final "Conclusion" (misma versión que el hero)

**Patrones identificados:**
- Estructura más simple que otras páginas
- Menos componentes anidados
- Usa `text_container` con `text_content_version: disclaimer` para destacar información importante
- El CTA final usa la misma versión que el hero (`cta_section_19`)

---

## 🎯 Resumen de Patrones Comunes

### Componentes que SIEMPRE aparecen (en orden):
1. `cta_section_19` - Hero principal
2. `trust_badges_1` - Trust badges
3. `info_4` - "Join 1M+ Entrepreneurs"

### Componentes que aparecen frecuentemente:
- `table_of_contents_v1` - Contenido principal (SIEMPRE presente)
- `faq_1` - FAQs (mayoría de páginas)
- `info_group_tabs_v8` - Tools tabs (algunas páginas)
- `disclaimer_text_1` - Disclaimers (mayoría de páginas)
- `cta_section_*` - CTA final (SIEMPRE presente)

### Versiones de componentes más comunes:
- `cta_section`: `cta_section_19` (hero), `cta_section_9`, `cta_section_21`, `cta_section_2` (finales)
- `info`: `info_4` (estándar "Join 1M+"), `info_28`, `info_29`, `info_11`
- `table_of_contents`: `table_of_contents_v1` (única versión encontrada)
- `faq`: `faq_1` (única versión encontrada)
- `disclaimer_text`: `disclaimer_text_1` (única versión encontrada)
- `trust_badges`: `trust_badges_1` (única versión encontrada)
- `info_group_tabs`: `info_group_tabs_v8` (única versión encontrada)
- `text_with_icon`: `version: gray`, `version: white`
- `text_container`: `text_content_version: disclaimer` (o `null`)
- `video`: `version: video_1` (única versión encontrada)
- `article_button`: `toc_version: article_button_1` (única versión encontrada)

---

## 📝 Próximos Pasos

1. ✅ Analizar páginas migradas - COMPLETADO
2. ✅ Documentar patrones de mapeo - COMPLETADO
3. ⏳ Crear helpers PHP para generar componentes automáticamente
4. ⏳ Crear script de conversión HTML → Statamic
5. ⏳ Documentar casos edge y excepciones
6. ⏳ Crear script de verificación

---

---

## ⚠️ ERRORES COMUNES IDENTIFICADOS EN MIGRACIONES

### Errores encontrados en `digital-nomad-business`:

1. **Videos en `table_of_contents`:**
   - ❌ **ERROR:** Crear items de texto separados para "How to become a digital nomad?" y "WATCH" en lugar de un componente `video`
   - ✅ **CORRECTO:** Usar componente `type: video` con `version: video_1` y `video_url` real (ej: YouTube embed URL)
   - ✅ El componente `video` debe incluir todos los campos necesarios: `bard_alignment`, `secondary_font`, `custom_font_size`, `show_mobile`, `show_tablet`, `show_desktop`, `show_arrow`, `show_video_object`

2. **Estructura de contenido dentro de `table_of_contents`:**
   - ❌ **ERROR:** Separar el "Digital Nomad Business Formation Guide" en un `toc_items` separado
   - ✅ **CORRECTO:** Consolidar contenido relacionado dentro del mismo `toc_items` usando texto multilinea con formato (`text: |-` con `###` para headings y `<br>` para saltos de línea)
   - ✅ Usar texto multilinea para listas con bullets (`*`) y párrafos con `<br><br>` entre ellos

3. **Estadísticas vs Imágenes:**
   - ❌ **ERROR:** Usar `text_container` con `text_content_version: disclaimer` para todas las estadísticas
   - ✅ **CORRECTO:** Verificar si hay una imagen asociada en producción. Si hay una imagen, usar `type: image_with_description` con el nombre del archivo real (ej: `digital-nomad-info-1.webp`)
   - ✅ Solo usar `text_container` cuando NO hay imagen asociada

4. **Consolidación de secciones en `table_of_contents`:**
   - ❌ **ERROR:** Crear múltiples componentes `table_of_contents` separados para diferentes secciones
   - ✅ **CORRECTO:** Consolidar secciones relacionadas dentro del mismo `table_of_contents` usando múltiples `toc_items` dentro del mismo componente
   - ✅ Ejemplo: "Jobs You Can Do From Anywhere", "Starting Your Digital Nomad Business", "Setting up a Business Entity", y "Taxes when Working Remotely" pueden estar en el mismo `table_of_contents` como diferentes `toc_items`

5. **Integración de componentes separados:**
   - ❌ **ERROR:** Crear componentes `info_28` o `disclaimer_text_1` separados cuando el contenido está relacionado con el `table_of_contents`
   - ✅ **CORRECTO:** Integrar contenido relacionado dentro del `table_of_contents` como items de texto con formato (`###` para headings, `<br>` para saltos)
   - ✅ Solo crear componentes separados cuando son secciones completamente independientes

6. **Links en texto:**
   - ❌ **ERROR:** Dejar links como texto plano sin formato
   - ✅ **CORRECTO:** Usar formato markdown para links dentro del texto: `[texto del link](URL)`
   - ✅ Los links markdown funcionan dentro de items `type: text` con formato multilinea

7. **Imágenes:**
   - ❌ **ERROR:** Dejar imágenes como `null` o placeholder cuando hay imágenes reales en producción
   - ✅ **CORRECTO:** Verificar en producción qué imágenes hay y usar los nombres de archivo reales (ej: `digital-nomad-info-1.webp`, `digital-nomad-info-2.webp`)
   - ✅ Las imágenes deben estar en `type: image_with_description` con el campo `image` con el nombre del archivo

8. **Assets de iconos:**
   - ❌ **ERROR:** Usar assets genéricos como `laptop-duocolor.svg`, `gear-duocolor.svg` sin verificar variantes
   - ✅ **CORRECTO:** Verificar en producción qué variantes de iconos se usan (ej: `code-rec-blue-duocolor.svg`, `gear-blue-duocolor.svg`, `megaphone-duocolor-tertiary.svg`, `chart-line-blue-duocolor.svg`, `pin-blue-duocolor.svg`)
   - ✅ Algunos iconos pueden estar en subdirectorios como `icons/folder-open.svg` o `icons/laptop-duocolor3.svg`

9. **Background en `info_28`:**
   - ❌ **ERROR:** No incluir campo `background` cuando es necesario
   - ✅ **CORRECTO:** Agregar `background: '#ffffff'` cuando el componente necesita un fondo específico

10. **CTA Section para checklist:**
    - ❌ **ERROR:** Usar `cta_section_9` para el checklist de "Start Your Business"
    - ✅ **CORRECTO:** Usar `cta_section_21` con `title` como `heading` (level 2) y `description` como `paragraph`
    - ✅ El campo `description` debe estar después de `images` en la estructura

11. **Campos adicionales en `info_group_tabs`:**
    - ❌ **ERROR:** No incluir campos adicionales al final del componente
    - ✅ **CORRECTO:** Incluir campos `toggle`, `alignment`, `button_width`, `capitalized` al final del componente `info_group_tabs` cuando sean necesarios

12. **Links en `info_group_tabs`:**
    - ❌ **ERROR:** Usar URLs genéricas sin verificar variantes (ej: `/es` para español)
    - ✅ **CORRECTO:** Verificar las URLs reales en producción, pueden tener variantes de idioma o rutas específicas

### ⚠️ REGLAS CRÍTICAS PARA FUTURAS MIGRACIONES:

1. **Siempre verificar la estructura en producción:** No asumir cómo está estructurado el contenido, revisar la página real
2. **Consolidar contenido relacionado:** Preferir consolidar dentro de `table_of_contents` en lugar de crear múltiples componentes separados
3. **Verificar assets reales:** No usar placeholders, verificar nombres de archivos reales para imágenes e iconos
4. **Usar formato markdown para links:** Los links dentro de texto deben usar formato `[text](url)`
5. **Videos deben ser componentes completos:** No usar texto plano para videos, usar `type: video` con todos los campos necesarios
6. **Texto multilinea con formato:** Usar `text: |-` con `###` para headings y `<br>` para saltos cuando se consolida contenido

---

## 📚 Componente `info_30` - Listas de Grupos/Foros con Links

### Patrón Identificado:

El componente `info_30` se usa para crear listas de grupos de redes sociales, foros de discusión, o comunidades en línea donde cada item es un link clickeable.

**Ejemplos encontrados:**
- `digital-nomad-business`: "Social Media Groups for Your Digital Nomad Business"
- `digital-nomad-business`: "Discussion Groups and Forums for Your Digital Nomad Business"

### Estructura del Componente:

```yaml
-
  id: [UUID único]
  version: info_30
  title:
    -
      type: heading
      attrs:
        level: 2
      content:
        -
          type: text
          marks:
            -
              type: bold
          text: 'Título de la sección'
  description: 'Descripción introductoria de la sección'
  order_reverse: false
  show_index: true
  items:
    -
      id: [UUID único]
      title:
        -
          type: paragraph
          content:
            -
              type: text
              text: 'Nombre del grupo/foro'
      select_an_icon: false
      url: 'https://url-del-grupo.com/'
      type: new_item
      enabled: true
  show_display_options: false
  type: info
  enabled: true
  details:
    use_eyebrow: null
    use_only_principal_image: null
  button:
    label: null
    mobile_label: null
    link: null
    target_blank: null
    custom_size: null
    custom_alignment: null
    custom_alignment_by_viewport: null
    capitalized: null
    custom_icon: null
    size: null
    button_width: null
    variant: null
    alignment: null
    mobile_alignment: null
    tablet_alignment: null
    desktop_alignment: null
    custom_text_align: null
    text_align: null
    icon: null
    position_icon: null
    wrapper_class: null
  group_button:
    toggle: null
    label: null
    label_mobile: null
    alignment: null
    link: null
    navigate_to_section: null
    button_variant: null
    button_size: null
    target_blank: null
    button_width: null
    capitalized: null
    wrapper_class: null
```

### Características Clave:

1. **Versión:** Siempre usar `version: info_30`
2. **Título:** Usar `type: heading` con `level: 2` y texto en `bold`
3. **Descripción:** Texto plano que introduce la lista
4. **Items:** Cada item tiene:
   - `title` como `paragraph` con el nombre del grupo/foro
   - `select_an_icon: false` siempre
   - `url` con la URL completa del grupo/foro
   - `type: new_item`
   - `enabled: true`
5. **Campos estándar:** Todos los campos `button` y `group_button` deben estar presentes con valores `null`
6. **show_display_options:** Siempre `false`
7. **order_reverse:** Generalmente `false`
8. **show_index:** Generalmente `true`

### Cuándo Usar `info_30`:

- ✅ Listas de grupos de redes sociales (Facebook, LinkedIn, etc.)
- ✅ Listas de foros de discusión en línea
- ✅ Listas de comunidades/redes donde cada item es un link externo
- ✅ Cualquier lista donde los items son links clickeables a recursos externos

### Ejemplo Real (`digital-nomad-business`):

```yaml
-
  id: mj396disc
  version: info_30
  title:
    -
      type: heading
      attrs:
        level: 2
      content:
        -
          type: text
          marks:
            -
              type: bold
            text: 'Discussion Groups and Forums for Your Digital Nomad Business'
  description: 'There are plenty of forums and online discussion groups for digital nomad businesses. Start with these:'
  order_reverse: false
  show_index: true
  items:
    -
      id: mj396d01
      title:
        -
          type: paragraph
          content:
            -
              type: text
              text: Nomad List
      select_an_icon: false
      url: 'https://nomadlist.com/'
      type: new_item
      enabled: true
    # ... más items
```

---

## 🔄 Diferencias entre `cta_section_9` y `cta_section_21`

### `cta_section_9` - CTA con Imagen Destacada

**Cuándo usar:**
- ✅ CTAs que promocionan recursos específicos (ej: "The Complete 'Start Your Business' Checklist")
- ✅ Cuando hay una imagen asociada que debe mostrarse
- ✅ Layout de dos columnas (texto izquierda, imagen derecha)

**Estructura:**
```yaml
-
  id: [UUID único]
  version: cta_section_9
  title:
    -
      type: paragraph  # ⚠️ NO heading, solo paragraph
      content:
        -
          type: text
          text: "The Complete 'Start Your Business' Checklist"
  description:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'A Clear and Comprehensive Guide to Starting Your Business the Right Way'
  images:
    image: business-checklist.webp  # ⚠️ Imagen real, NO null
    mobile_image: null
  button:
    label: 'Business Checklist'
    # ... resto de campos
  type: cta_section
  enabled: true
  # ... resto de campos select y hero_button
```

**Características clave:**
- `title`: `type: paragraph` (NO `heading` con level 2)
- `description`: Viene ANTES de `images` en el orden
- `images.image`: Debe ser un archivo real (ej: `business-checklist.webp`), NO `null`
- Layout: Dos columnas con imagen a la derecha

### `cta_section_21` - CTA Final Simple

**Cuándo usar:**
- ✅ CTAs finales de página (ej: "Ready to Start Your Digital Nomad Business?")
- ✅ Cuando NO hay imagen asociada
- ✅ Layout centrado y simple

**Estructura:**
```yaml
-
  id: [UUID único]
  version: cta_section_21
  title:
    -
      type: paragraph
      content:
        -
          type: text
          marks:
            -
              type: bold  # ⚠️ Puede tener bold
          text: Conclusion
  description:
    -
      type: paragraph
      content:
        -
          type: text
          text: "Texto descriptivo..."
  images:
    image: null  # ⚠️ Generalmente null
    mobile_image: null
  button:
    label: 'Start your Digital Nomad Business with us, today'
    # ... resto de campos
  type: cta_section
  enabled: true
  # ... resto de campos select y hero_button
```

**Características clave:**
- `title`: `type: paragraph` (puede tener `bold`)
- `description`: Viene DESPUÉS de `images` en el orden
- `images.image`: Generalmente `null`
- Layout: Centrado, sin imagen

### ⚠️ REGLA CRÍTICA:

**NO confundir las versiones:**
- `cta_section_9` = CTA con imagen (checklist, recursos específicos)
- `cta_section_21` = CTA final sin imagen (conclusión de página)

---

## 🔗 Links Markdown en Texto Multilinea

### Patrón Identificado:

Los links markdown `[text](url)` funcionan perfectamente dentro de items `type: text` con formato multilinea (`text: |-`) dentro de `table_of_contents`.

**Ejemplo Real:**
```yaml
-
  id: mj36pveg
  text: |-
    Next, you have more choice than most entrepreneurs...
    <br>For more information on the advantages and disadvantages of different types of businesses, [please see our in-depth guide](https://bizee.com/form/business-entity-comparison).
    <br>We've [got a complete guide to everything you need to do to set up an LLC](https://bizee.com/form-an-llc), and we can start one for your consulting business today. [LLC formation does vary from state to state](https://bizee.com/llc-state-information), but we've got you covered, wherever you are.
  type: text
  enabled: true
```

### Cuándo Usar Links Markdown:

- ✅ Dentro de texto multilinea en `table_of_contents`
- ✅ Cuando el link está integrado en el flujo del texto
- ✅ Para links internos o externos que complementan el contenido

### Formato:

- Usar formato markdown estándar: `[texto del link](URL)`
- Puede combinarse con `<br>` para saltos de línea
- Funciona dentro de párrafos largos con múltiples links

---

## ✅ Comillas Tipográficas en Componentes `info_30`

### Patrón Identificado:

Los textos en items de `info_30` deben usar comillas tipográficas (`'`) en lugar de comillas simples rectas (`'`).

**Ejemplo:**
```yaml
-
  id: mj396d01
  title:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Nomad List'  # ⚠️ Comillas tipográficas alrededor del texto
  select_an_icon: false
  url: 'https://nomadlist.com/'
  type: new_item
  enabled: true
```

### Regla:

- ✅ Siempre usar comillas tipográficas (`'`) en textos de items
- ✅ Aplicar la misma regla que para todo el contenido: reemplazar todas las comillas simples rectas (`'`) por tipográficas (`'`)

---

**Última actualización:** 2025-01-XX
