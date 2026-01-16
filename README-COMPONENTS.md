# Componentes Disponibles para Páginas

Este documento describe los componentes disponibles para las páginas en la colección `start_a_business_pages`.

## 📋 Lista de Componentes

### 1. `cta_section`

Sección de call-to-action con título, descripción, imagen opcional y botón.

**Estructura básica:**
```yaml
-
  id: [UUID único]
  version: cta_section_19  # o cta_section_9, cta_section_21, etc.
  title:
    -
      type: heading
      attrs:
        level: 1  # o 2
      content:
        -
          type: text
          marks:
            -
              type: bold
          text: 'Título del CTA'
  description:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Descripción del CTA'
  images:
    image: null  # o ruta de imagen
    mobile_image: null
  button:
    label: 'Texto del botón'  # ⚠️ Si no hay botón en producción, usar: label: null
    mobile_label: null
    link: 'https://...'
    target_blank: false
    custom_size: false
    custom_alignment: true
    custom_alignment_by_viewport: false
    capitalized: false
    custom_icon: false
    size: null
    button_width: null
    variant: primary
    alignment: mx-auto
    # ... más campos opcionales
  type: cta_section
  enabled: true
```

**⚠️ IMPORTANTE:** Si en producción el `cta_section_19` NO tiene botón visible, debes establecer `button.label: null`. El template verifica `{{ if button.label }}` antes de mostrar el botón.

### 2. `trust_badges`

Badges de confianza (Trustpilot, Shopper Approved).

**Estructura básica:**
```yaml
-
  id: [UUID único]
  external_page:
    -
      id: [UUID único]
      review_pages: trustpilot  # o shopper_approved
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

### 3. `info`

Bloques de información con items. Puede tener título, descripción y lista de items.

**Estructura básica:**
```yaml
-
  id: [UUID único]
  version: info_4  # o info_28, info_29, info_11, etc.
  title:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Título'
  description: 'Descripción opcional'
  order_reverse: false
  show_index: true
  show_display_options: false
  asset: inc5000_x2.webp  # opcional
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
      asset: checkbox-circle-blue-duocolor.svg  # opcional
      select_an_icon: false
      type: new_item
      enabled: true
  background: '#fafafa'  # opcional
  type: info
  enabled: true
```

### 4. `table_of_contents`

Tabla de contenidos con items anidados. Puede contener texto, imágenes, videos, botones, etc.

**Estructura básica:**
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
          text: 'On this page'
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
        -
          id: [UUID único]
          text: 'Texto del item'
          type: text
          enabled: true
        -
          id: [UUID único]
          version: gray  # o white
          text: 'Texto con icono'
          icon: checkbox-circle-duocolor.svg
          type: text_with_icon
          enabled: true
        -
          id: [UUID único]
          text_content_version: disclaimer
          text: '### Título\n\nContenido'
          type: text_container
          enabled: true
        -
          id: [UUID único]
          image: pages/table-of-content/image.webp
          type: image_with_description
          enabled: true
        -
          id: [UUID único]
          toc_label: 'Texto del botón'
          toc_link: 'https://...'
          toc_target_blank: false
          toc_variant: primary
          type: simple_button
          enabled: true
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
        -
          id: [UUID único]
          version: video_1
          video_url: 'https://incfile.wistia.com/medias/[VIDEO_ID]'
          show_video_object: false
          type: video
          enabled: true
      type: toc_items
      enabled: true
  type: table_of_contents
  enabled: true
```

### 5. `disclaimer_text`

Texto de disclaimer/disclaimer.

**Estructura básica:**
```yaml
-
  id: [UUID único]
  version: disclaimer_text_1
  content:
    -
      type: paragraph
      attrs:
        textAlign: left
      content:
        -
          type: text
          text: 'Texto del disclaimer'
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

### 6. `faq`

Preguntas frecuentes con formato FAQ.

**Estructura básica:**
```yaml
-
  id: [UUID único]
  version: faq_1
  seo_data_type: faq
  title:
    -
      type: paragraph
      content:
        -
          type: text
          text: 'Título de la sección FAQ'
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

**También puede aparecer dentro de `table_of_contents`:**
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
      select_an_icon: false
      type: new_item
      enabled: true
  toc_toggle: false
  toc_alignment: default
  toc_button_width: w-full
  toc_capitalized: false
  type: faq
  enabled: true
```

### 7. `info_group_tabs`

Grupos de información organizados en tabs.

**Estructura básica:**
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
          text: 'Título'
  description: 'Descripción opcional'
  badge_version: badge_primary
  show_icon_in_mobile: false
  tab_group:
    -
      id: [UUID único]
      version: tab_group_v1
      toggle: false
      label: 'Nombre del Tab'
      alignment: default
      button_width: w-full
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
                  text: 'Título del item'
          link: 'https://...'
          type: new_set
          enabled: true
      type: new_set
      enabled: true
  toggle: false
  alignment: default
  button_width: w-full
  capitalized: false
  type: info_group_tabs
  enabled: true
```

### 8. `text`

Componente de texto simple (Bard content).

**Estructura básica:**
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
            level: 2
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
      bard_alignment: false
      secondary_font: false
      custom_font_size: false
      show_display_options: false
      type: text
      enabled: true
  type: components
  enabled: true
```

### 9. `video`

Videos de Wistia.

**Estructura básica:**
```yaml
-
  id: [UUID único]
  version: video_1
  video_url: 'https://incfile.wistia.com/medias/[VIDEO_ID]'
  bard_alignment: false
  secondary_font: false
  custom_font_size: false
  show_mobile: false
  show_tablet: false
  show_desktop: false
  show_arrow: false
  show_video_object: false
  type: video
  enabled: true
```

### 10. `blocks`

Contenedor de bloques anidados.

**Estructura básica:**
```yaml
-
  id: [UUID único]
  blocks:
    -
      id: [UUID único]
      # ... cualquier otro componente aquí
      type: [tipo_de_componente]
      enabled: true
  type: blocks
  enabled: true
```

## 📝 Notas Importantes

1. **Todos los componentes deben tener:**
   - `id`: UUID único
   - `type`: Tipo del componente
   - `enabled: true`

2. **Versiones:** Cada componente tiene diferentes versiones (ej: `cta_section_19`, `info_28`, etc.). Usa la versión apropiada según el contexto.

3. **IDs únicos:** Cada bloque, item, tab, etc. debe tener su propio UUID único.

4. **Formato Bard:** El contenido de texto usa formato Bard de Statamic.

5. **Comillas dobles:** Siempre usar comillas dobles (`"`) para strings en YAML.

## 🔍 Cómo Elegir el Componente Correcto

- **Call-to-action con botón:** `cta_section`
- **Tabla de contenidos con items:** `table_of_contents`
- **Lista de información con items:** `info`
- **Preguntas frecuentes:** `faq`
- **Disclaimers:** `disclaimer_text`
- **Grupos de información con tabs:** `info_group_tabs`
- **Texto simple:** `text` (dentro de `components`)
- **Videos:** `video`
- **Badges de confianza:** `trust_badges`

## 📚 Referencias

- Revisa ejemplos en `content/collections/start_a_business_pages/` para ver cómo se usan estos componentes en la práctica.
