# 🤖 Instrucciones para Migrar Páginas con Cursor AI

Este documento contiene las instrucciones exactas que debes darle a tu agente de Cursor AI para migrar páginas correctamente.

## 📋 Instrucciones Iniciales para el Agente

Copia y pega estas instrucciones al inicio de cada sesión de migración con Cursor:

```
Eres un asistente especializado en migrar páginas de producción a Statamic CMS (colección start_a_business_pages).

ANTES de empezar cualquier migración, DEBES leer estos documentos en orden:
1. llc-v2-pages-migration/CRITICAL-CHECKLIST.md - ⚠️ OBLIGATORIO
2. llc-v2-pages-migration/QUICK-START.md - Guía rápida
3. llc-v2-pages-migration/README.md - Documentación general

REGLAS CRÍTICAS QUE NUNCA DEBES OLVIDAR:
- ⚠️ **CRÍTICO: TODO el contenido DEBE obtenerse directamente de la URL original de producción**
  - NUNCA usar contenido de otras páginas similares como referencia
  - NUNCA asumir que el contenido es igual a otras páginas
  - SIEMPRE extraer el contenido exacto usando `curl` de la URL original
  - SIEMPRE verificar cada sección, cada texto, cada link directamente de la URL original
  - Si hay dudas sobre qué componente usar o qué contenido incluir, SIEMPRE consultar la URL original primero
- ⚠️ **OBLIGATORIO: Si no reconoces qué componente usar para una sección específica**
  - DETENTE y PREGUNTA al usuario qué componente debe usarse
  - NUNCA adivines o inventes la estructura de un componente
  - SIEMPRE espera confirmación del usuario antes de continuar con secciones ambiguas
  - Es mejor preguntar que crear una estructura incorrecta
- ⚠️ **CRÍTICO: NO usar meta tags como fuente del contenido visible**
  - Las páginas están renderizadas con React/Next.js, por lo que el HTML estático solo contiene meta tags
  - El contenido visible real está renderizado por JavaScript y NO está disponible en el HTML estático
  - ⚠️ **NUNCA** usar `<meta name="description">` como fuente del contenido visible de CTAs, descripciones, etc.
  - ⚠️ **SIEMPRE** obtener el contenido visible real de la página renderizada
  - Si no puedes obtener el HTML renderizado con `curl`, debes:
    1. Usar herramientas como Playwright o Selenium para renderizar el JavaScript, O
    2. Pedir al usuario el contenido exacto cuando sea necesario
  - El meta tag de descripción puede ser diferente al contenido visible real de la página
- ⚠️ NUNCA inventar contenido - TODO debe ser exacto de producción
- ⚠️ NUNCA copiar UUIDs de otras páginas - SIEMPRE generar uno nuevo único
- ⚠️ SIEMPRE subir TODAS las imágenes a S3
- ⚠️ SIEMPRE verificar TODOS los links del contenido principal
- ⚠️ SIEMPRE usar comillas dobles (") para strings en YAML
- ⚠️ COMPONENTES OBLIGATORIOS (SIEMPRE deben estar):
  - `cta_section_19` - Hero CTA (siempre primero)
    - ⚠️ Si NO hay botón en producción, usar `button.label: null`
  - `trust_badges_1` - Trust Badges (siempre segundo)
  - `info_4` - Join 1M+ Info (siempre tercero)
- ⚠️ COMPONENTES OPCIONALES (SOLO si están en producción):
  - NUNCA agregar componentes que no estén visibles en la página original de producción
  - Solo agregar table_of_contents, info blocks adicionales, faq, disclaimer, video, CTA final, etc. si están presentes en producción
- ⚠️ SIEMPRE incluir id, type, enabled: true y version en cada bloque
- ⚠️ **BOTONES DENTRO DE TOC**: Cuando agregues botones dentro de los items del TOC (table_of_contents):
  - SIEMPRE usar `type: simple_button` (NO usar `type: button`)
  - SIEMPRE usar campos con prefijo `toc_`: `toc_label`, `toc_link`, `toc_target_blank`, `toc_custom_size`, `toc_custom_alignment`, `toc_capitalized`, `toc_custom_icon`, `toc_variant`, `toc_custom_text_align`
  - Ver ejemplo completo en sección "Instrucciones para Componentes"

Cuando migres una página, sigue este proceso paso a paso y verifica cada punto antes de continuar.
```

## 🎯 Instrucciones para Migrar una Página Específica

Cuando tengas una página específica para migrar, usa este formato:

```
Migra la página [URL] a la colección start_a_business_pages

Ejemplo:
Migra https://bizee.com/start-a-business/travel-agency a start_a_business_pages

PROCESO OBLIGATORIO:
1. Primero lee llc-v2-pages-migration/CRITICAL-CHECKLIST.md completo
2. ⚠️ **CRÍTICO: Extrae el contenido completo de producción usando curl directamente de la URL original**
   - Ejemplo: `curl -s "https://bizee.com/start-a-business/[slug]" > /tmp/page.html`
   - NUNCA uses contenido de otras páginas similares como referencia
   - SIEMPRE verifica cada sección directamente de la URL original
   - ⚠️ **IMPORTANTE: Las páginas están renderizadas con React/Next.js**
     - El HTML estático solo contiene meta tags, NO el contenido visible renderizado
     - ⚠️ **NUNCA** uses `<meta name="description">` como fuente del contenido visible (CTAs, descripciones, etc.)
     - El contenido visible real está en el HTML renderizado por JavaScript
     - Si `curl` no obtiene el contenido visible, usa Playwright/Selenium o pide el contenido exacto al usuario
3. Identifica TODAS las imágenes y súbelas a S3
4. Crea el archivo markdown con estructura correcta en content/collections/start_a_business_pages/[slug].md
5. Verifica TODOS los links del contenido principal (extraídos directamente de la URL original)
6. Convierte contenido HTML a componentes apropiados (cta_section, table_of_contents, info, etc.)
   - ⚠️ Si hay dudas sobre qué componente usar, consulta la URL original primero
7. Configura campos SEO exactos de producción (extraídos directamente de la URL original)
8. ⚠️ CRÍTICO: Agrega la ruta `/start-a-business/[slug]` a `app/Routing/migration/released-pages.php`
9. ⚠️ OBLIGATORIO: Ejecuta el script de reemplazo de comillas: `python3 llc-v2-pages-migration/replace_quotes_final.py content/collections/start_a_business_pages/[slug].md`
10. Verifica el checklist crítico completo antes de terminar

IMPORTANTE:
- ⚠️ **CRÍTICO: TODO el contenido DEBE obtenerse directamente de la URL original**
  - NUNCA uses otras páginas similares como referencia para el contenido
  - SIEMPRE extrae el contenido exacto de la URL original usando curl
  - Si hay dudas sobre qué incluir, consulta la URL original primero
- El UUID debe ser único (generar nuevo, nunca copiar)
- Las imágenes deben estar en S3, no locales
- Todos los links del contenido deben estar incluidos (extraídos de la URL original)
- El contenido debe ser EXACTO de producción, nunca inventado
- COMPONENTES OBLIGATORIOS: Solo los primeros 3 (cta_section_19, trust_badges_1, info_4)
- COMPONENTES OPCIONALES: Solo agregar si están presentes en producción (verificado en la URL original)
- NUNCA agregar componentes que no estén en la página original
```

## 📝 Checklist de Verificación para el Agente

Después de que el agente complete la migración, pídele que verifique:

```
Verifica que la migración esté completa usando el checklist crítico:

1. ✅ UUID único (no duplicado de otra página)
2. ✅ TODAS las imágenes subidas a S3
3. ✅ TODOS los links del contenido principal incluidos en formato Bard
4. ✅ Componentes usando tipos correctos (cta_section, table_of_contents, info, faq, etc.)
5. ✅ Contenido exacto de producción (nada inventado)
6. ✅ Todos los bloques tienen id, type, enabled: true y version
7. ✅ Campos SEO completos (seo_custom_meta_title y seo_custom_meta_description de producción)
8. ✅ Todos los strings usan comillas dobles (") - Verificado con script `replace_quotes_final.py`
9. ✅ Todos los videos de Wistia están incluidos como bloques video
10. ✅ ⚠️ CRÍTICO: Ruta agregada a `app/Routing/migration/released-pages.php` (formato: `/start-a-business/[slug]`)
11. ✅ ⚠️ OBLIGATORIO: Script `replace_quotes_final.py` ejecutado para reemplazar comillas simples por tipográficas

Si falta algo, corrígelo inmediatamente.
```

## 🔍 Instrucciones para Verificar Links

Cuando necesites verificar links, pídele al agente:

```
Verifica que TODOS los links del contenido principal estén incluidos:

1. Extrae todos los links de producción usando curl
2. Filtra SOLO los links del contenido principal (excluir header, footer, sidebar)
3. Compara uno por uno con la página migrada
4. Si falta algún link, agrégalo inmediatamente en formato Bard correcto
5. Verifica que links externos tengan rel: "noopener noreferrer" y target: _blank
6. Verifica que links internos tengan rel: null, target: null, title: null

Esta verificación es OBLIGATORIA y debe hacerse al final de cada migración.
```

## 🖼️ Instrucciones para Imágenes

Cuando necesites manejar imágenes, pídele al agente:

```
Procesa TODAS las imágenes de la página:

1. Identifica TODAS las imágenes del contenido
2. Súbelas a S3 usando el script apropiado o directamente
3. Verifica que todas las imágenes aparezcan correctamente referenciadas
4. NUNCA dejes imágenes locales - SIEMPRE deben estar en S3

⚠️ CRÍTICO: Todas las imágenes deben estar subidas.
```

## 🧩 Instrucciones para Componentes

Cuando necesites usar componentes, pídele al agente:

```
Usa los componentes correctos según el tipo de contenido:

1. cta_section - Para secciones de call-to-action con título, descripción y botón
2. table_of_contents - Para tablas de contenido con items anidados
3. info - Para bloques de información con items
4. faq - Para preguntas frecuentes
5. disclaimer_text - Para disclaimers
6. video - Para videos de Wistia
7. text - Para contenido de texto simple
8. text_container - Para contenido de texto con formato especial (usar text_content_version: disclaimer)
9. info_group_tabs - Para grupos de información con tabs
10. simple_button - Para botones dentro de los items del TOC

⚠️ IMPORTANTE: Botones dentro de items del TOC (table_of_contents):
- SIEMPRE usar type: simple_button (NO usar type: button)
- SIEMPRE usar campos con prefijo toc_:
  - toc_label: 'Texto del botón'
  - toc_link: 'https://url.com'
  - toc_target_blank: false
  - toc_custom_size: false
  - toc_custom_alignment: false
  - toc_capitalized: false
  - toc_custom_icon: false
  - toc_variant: primary
  - toc_custom_text_align: false
- Ejemplo completo:
  - id: [uuid]
    toc_label: 'CHECK OUR INFLUENCER MEDIA KIT TEMPLATE'
    toc_link: 'https://bizee.com/resources/influencer-media-kit-template'
    toc_target_blank: false
    toc_custom_size: false
    toc_custom_alignment: false
    toc_capitalized: false
    toc_custom_icon: false
    toc_variant: primary
    toc_custom_text_align: false
    type: simple_button
    enabled: true

Cada componente debe tener:
- id: UUID único
- type: Tipo correcto del componente
- enabled: true
- version: Versión correcta del componente (cuando aplique)

Consulta llc-v2-pages-migration/README-COMPONENTS.md para más detalles.
```

## 🎨 Instrucciones para Iconos en Info 28 e Info 11

⚠️ **CRÍTICO: Reglas para Iconos en Componentes Info 28 e Info 11**

Todos los iconos de los componentes `info_28` e `info_11` SIEMPRE deben ser iconos azules. Los iconos disponibles son:

**Lista de Iconos Azules Disponibles:**
- `megaphone-duocolor-tertiary.svg`
- `book-open-blue-duocolor.svg`
- `briefcase-blue-duocolor.svg`
- `cash-blue-duocolor.svg`
- `chart-column-blue-duocolor.svg`
- `chart-line-blue-duocolor.svg`
- `checkbox-circle-blue-duocolor.svg`
- `code-rec-blue-duocolor.svg`
- `comment-2-text-blue-duocolor.svg`
- `comment-blue-duocolor.svg`
- `comments-2-blue-duocolor.svg`
- `credit-card-income-blue-duocolor.svg`
- `file-text-blue-duocolor.svg`
- `folder-heart-blue-duocolor.svg`
- `gear-blue-duocolor.svg`
- `glasses-blue-duocolor.svg`
- `globe-2-blue-duocolor.svg`
- `home-3-blue-duocolor.svg`
- `keyboard-blue-duocolor.svg`
- `newspaper-blue-duocolor.svg`
- `pin-blue-duocolor.svg`
- `scan-rec-blue-duocolor.svg`
- `search-blue-duocolor.svg`
- `shield-check-blue-duocolor.svg`
- `user-plus-blue-duocolor.svg`
- `users-blue-duocolor.svg`
- `wallet-blue-duocolor.svg`
- `wrench-blue-duocolor.svg`

**Reglas de Uso:**
1. ⚠️ **SIEMPRE** usar `select_an_icon: false` (NO usar `select_an_icon: true`)
2. ⚠️ **SIEMPRE** usar el campo `asset:` con uno de los iconos de la lista arriba
3. ⚠️ **SIEMPRE** buscar dentro de esta lista para hacer la migración
4. Si ves un icono que no está en la lista, busca el más parecido o pregunta al usuario cuál usar
5. Los iconos deben ser relevantes al contenido del item (por ejemplo: `pin-blue-duocolor.svg` para ubicación, `search-blue-duocolor.svg` para investigación, `comment-2-text-blue-duocolor.svg` para comunicación, etc.)

**Ejemplo Correcto:**
```yaml
- id: ftb20
  title:
    - type: paragraph
      content:
        - type: text
          marks:
            - type: bold
          text: 'Look at who your competitors are in the space'
  description: '...'
  select_an_icon: false
  asset: pin-blue-duocolor.svg
  type: new_item
  enabled: true
```

**Ejemplo Incorrecto:**
```yaml
- id: ftb20
  title: ...
  description: ...
  select_an_icon: true  # ❌ INCORRECTO
  asset: pin-duocolor.svg  # ❌ INCORRECTO (debe ser pin-blue-duocolor.svg)
  type: new_item
  enabled: true
```

## 🔤 Instrucciones para Script de Comillas

Al finalizar cada migración, SIEMPRE ejecuta el script de reemplazo de comillas:

```
Ejecuta el script de reemplazo de comillas:

python3 llc-v2-pages-migration/replace_quotes_final.py content/collections/start_a_business_pages/[slug].md

Este script reemplaza automáticamente las comillas simples por comillas tipográficas (U+2019)
en los campos de texto (text, description, label, title, mobile_label, button_label, label_mobile).

⚠️ OBLIGATORIO: Este paso debe ejecutarse SIEMPRE al finalizar cada migración.
```

## 📊 Instrucciones para SEO

Al final de cada migración, pídele al agente:

```
Configura campos SEO para la página migrada:

1. Extrae el título SEO del tag <title> de producción → seo_custom_meta_title
2. Extrae la meta description del tag <meta name="description"> de producción → seo_custom_meta_description
3. Configura seo_title: title (o custom si aplica)
4. Configura seo_meta_description: custom (o empty si aplica)
5. Configura seo_canonical: none
6. Configura seo_og_description: general
7. Configura seo_og_title: title
8. Configura seo_tw_title: title
9. Configura seo_tw_description: general

⚠️ ESTE PASO ES OBLIGATORIO y debe hacerse al final de cada migración.
```

## 🔗 Instrucciones para Agregar a Released Pages

Al final de cada migración, DESPUÉS de crear el archivo markdown, pídele al agente:

```
⚠️ CRÍTICO: Agrega la página a released-pages.php:

1. Abre el archivo app/Routing/migration/released-pages.php
2. Agrega la ruta en formato: '/start-a-business/[slug]'
   Ejemplo: Si el slug es 'dog-walking-business', agrega '/start-a-business/dog-walking-business'
3. Agrega la ruta al final del array o en orden alfabético
4. Verifica que la sintaxis PHP sea correcta (comas, comillas, etc.)
5. ⚠️ CRÍTICO: Sin este paso, la página NO será servida por Statamic

Ejemplo de cómo debe verse:
```php
return [
    // ... otras rutas existentes
    '/start-a-business/dog-walking-business',
];
```

⚠️ ESTE PASO ES OBLIGATORIO y debe hacerse DESPUÉS de crear el archivo markdown.
```

## ⚠️ Instrucciones de Emergencia

Si el agente olvida algo crítico, recuérdale:

```
⚠️ RECUERDA: [Punto crítico olvidado]

Ejemplos:
- "⚠️ RECUERDA: TODO el contenido DEBE obtenerse directamente de la URL original, NUNCA de otras páginas similares"
- "⚠️ RECUERDA: NUNCA usar meta tags como fuente del contenido visible - las páginas son React/Next.js y el contenido visible está renderizado por JavaScript"
- "⚠️ RECUERDA: Las imágenes deben estar en S3, no locales"
- "⚠️ RECUERDA: Todos los links del contenido deben estar incluidos (extraídos de la URL original)"
- "⚠️ RECUERDA: El UUID debe ser único, nunca copiado"
- "⚠️ RECUERDA: El contenido debe ser exacto de producción, nada inventado"
- "⚠️ RECUERDA: Si hay dudas sobre qué componente usar, consulta la URL original primero"
- "⚠️ RECUERDA: Usar componentes correctos según el tipo de contenido"
- "⚠️ RECUERDA: Agregar la ruta a app/Routing/migration/released-pages.php"
- "⚠️ RECUERDA: Ejecutar el script replace_quotes_final.py al finalizar la migración"
```

## 📚 Documentos de Referencia

Cuando el agente necesite información específica, dirígelo a:

- **Estructura:** `llc-v2-pages-migration/README-STRUCTURE.md`
- **Componentes:** `llc-v2-pages-migration/README-COMPONENTS.md`
- **Imágenes:** `llc-v2-pages-migration/README-IMAGES.md`
- **Links:** `llc-v2-pages-migration/README-LINKS.md`
- **SEO:** `llc-v2-pages-migration/README-SEO.md`

## 🎓 Ejemplo Completo de Conversación

```
Usuario: Migra https://bizee.com/start-a-business/travel-agency a start_a_business_pages

Agente: [Lee CRITICAL-CHECKLIST.md y QUICK-START.md]
        [Extrae contenido de producción]
        [Identifica y sube imágenes a S3]
        [Crea archivo markdown]
        [Convierte contenido a componentes apropiados]
        [Verifica links]
        [Configura SEO]
        [Agrega ruta a released-pages.php] ⚠️ CRÍTICO
        [Ejecuta script replace_quotes_final.py] ⚠️ OBLIGATORIO
        [Verifica checklist completo]

Usuario: Verifica que todos los links del contenido estén incluidos

Agente: [Extrae links de producción]
        [Compara con página migrada]
        [Agrega links faltantes]
        [Verifica formato Bard correcto]
```

## ✅ Checklist Final para el Usuario

Antes de considerar la migración completa, verifica manualmente:

- [ ] La página aparece correctamente en Statamic CP
- [ ] Todas las imágenes se ven correctamente
- [ ] Todos los links funcionan
- [ ] Los componentes están en las posiciones correctas
- [ ] El contenido coincide exactamente con producción
- [ ] ⚠️ La ruta está agregada en `app/Routing/migration/released-pages.php`
- [ ] ⚠️ El script `replace_quotes_final.py` fue ejecutado para reemplazar comillas simples por tipográficas

---

**Nota:** Estas instrucciones están diseñadas para trabajar con el agente de Cursor AI. Si el agente olvida algún punto crítico, recuérdaselo usando las "Instrucciones de Emergencia" arriba.
