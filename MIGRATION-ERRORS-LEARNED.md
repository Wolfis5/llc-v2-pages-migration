# Errores Comunes en Migraciones - Lecciones Aprendidas

Este documento documenta los errores comunes encontrados durante las migraciones para evitar repetirlos en el futuro.

## ⚠️ Errores Críticos Identificados

### 1. **UUID - Formato Incorrecto**
**Error:** Usar UUID v4 completo (ej: `5f81f27d-2aa4-495d-a860-a5911e9e742e`)
**Correcto:** Usar formato simple con slug (ej: `women-business-001`)

**Patrón correcto:** `[slug]-001`, `[slug]-002`, etc.

### 2. **Campos SEO - Valores Incorrectos**
**Errores:**
- `seo_title`: Usé `title` → Debe ser `custom`
- `seo_custom_meta_title`: Incluí "| Bizee" → Debe ser solo el título sin el pipe
- `seo_custom_meta_description`: Usé descripción completa → Debe ser la descripción del hero (más corta)

**Regla:**
- `seo_title: custom` cuando hay `seo_custom_meta_title`
- `seo_custom_meta_title`: Solo el título, sin "| Bizee"
- `seo_custom_meta_description`: Usar la descripción del hero CTA, no la meta description completa

### 3. **Links Incorrectos - URLs Erróneas**

#### Links de SBA (Small Business Administration):
- ❌ `/8a-business-development-program`
- ✅ `/women-owned-small-business-federal-contracting-program`

- ❌ `/local-assistance/find`
- ✅ `/local-assistance`

- ❌ `/lender-match-connects-you-lenders`
- ✅ `/lender-match`

#### Links Internos de Bizee:
- ❌ `/resources/business-name-generator`
- ✅ `/business-name-generator`

- ❌ No incluí link a state filing fees
- ✅ Debe incluir: `https://bizee.com/state-filing-fees`

#### Links de Certificación (no incluidos):
- ✅ `https://certify.sba.gov/` (SBA certification)
- ✅ `https://www.wbenc.org/certification` (Women's Business Enterprise National Council)
- ✅ `https://www.uswcc.org/certification` (U.S. Women's Chamber of Commerce)
- ✅ `https://nwboc.org/apply.html` (National Women Business Owners Corporation)
- ✅ `https://ephcc.org/certifications/` (El Paso Hispanic Chamber of Commerce)

#### Links Adicionales Faltantes:
- ✅ `https://www.census.gov/econ/bfs/index.html` (Census Bureau reports)
- ✅ `https://gusto.com/company-news/new-business-owner-survey-2022` (49 percent statistic)
- ✅ `https://bizee.com/blog/women-in-business-statistics` (women in business statistics)
- ✅ Links a otras páginas de start-a-business (handmade items, Amazon seller, etc.)

### 4. **Botones - Link del CTA Hero Incorrecto**
**Error:** Asumir que el link del CTA hero tiene parámetros UTM o `entityType` sin verificar producción
**Correcto:**
- **SIEMPRE** extraer el link exacto del HTML de producción
- **NUNCA** agregar parámetros que no estén en producción
- **Verificación obligatoria:** `curl -s "https://bizee.com/start-a-business/[slug]" | grep -o 'href="[^"]*form-order-now[^"]*"'`
- Ejemplo correcto (construction-company): `https://orders.bizee.com/form-order-now.php` (sin parámetros)
- Si producción tiene parámetros, copiar TODOS exactamente
- Esta regla aplica SOLO al CTA hero inicial (`cta_section_19`), no al CTA final

### 5. **Estructura de Table of Contents - Componentes Incorrectos**

#### Error: Usar `text_with_icon` para títulos de secciones
**Correcto:** Usar `text_container` con `text_content_version: disclaimer` para:
- Listas con viñetas
- Secciones con títulos (usando `### Título`)
- Contenido estructurado con múltiples elementos

#### Error: Separar contenido relacionado en múltiples items
**Correcto:** Consolidar contenido relacionado usando `<br>` para saltos de línea dentro de un solo item `text`

**Ejemplo:**
```yaml
# ❌ INCORRECTO (múltiples items separados)
- id: wb08
  text: "Primer párrafo"
  type: text
- id: wb09
  version: gray
  text: "**Título**"
  icon: checkbox-circle-duocolor.svg
  type: text_with_icon
- id: wb10
  text: "Segundo párrafo"
  type: text

# ✅ CORRECTO (consolidado con text_container)
- id: wb08a
  text: |-
    Primer párrafo
    <br>
    ### Título
    <br>
    Segundo párrafo
  type: text
```

#### Error: Crear sección "Introduction" separada
**Correcto:** Integrar la introducción directamente en la primera sección principal

### 6. **FAQ - Configuración Incorrecta**
**Errores:**
- `list_mode: always` → Debe ser `never`
- Faltaba `attrs: textAlign: left` en títulos y descripciones
- Descripciones muy cortas → Deben incluir más contexto

**Estructura correcta:**
```yaml
- id: wb11
  version: faq_1
  title:
    - type: paragraph
      attrs:
        textAlign: left
      content:
        - type: text
          text: 'Título FAQ'
  list_mode: never  # ← Importante
  items:
    - id: wb12
      title:
        - type: paragraph
          attrs:
            textAlign: left  # ← Importante
          content:
            - type: text
              text: 'Pregunta'
      description: |-
        Descripción completa con contexto
        <br>Segunda línea si es necesario
      type: new_item
      enabled: true
      select_an_icon: false
```

### 7. **CTA Final - Versión y Contenido Incorrectos**
**Errores:**
- Usé `cta_section_19` → Debe ser `cta_section_9`
- Título incompleto → Debe incluir "with Bizee"
- Faltaba imagen → Debe tener `image: cta-big-banner_llc.webp`
- Link tenía parámetros UTM → Debe ser link simple

**Estructura correcta:**
```yaml
- id: wb15
  version: cta_section_9  # ← No cta_section_19
  title:
    - type: paragraph
      content:
        - type: text
          text: 'Go Beyond Girl Power: Jump-Start Your Business with Bizee'  # ← Incluir "with Bizee"
  description:
    - type: paragraph
      content:
        - type: text
          text: 'You focus on running your business — we'll handle everything else.'
  images:
    image: cta-big-banner_llc.webp  # ← Incluir imagen
    mobile_image: null
  button:
    label: 'Start Your Dream Business'
    link: 'https://orders.bizee.com/form-order-now.php'  # ← Sin parámetros UTM
```

### 8. **Formato de Texto - Saltos de Línea**
**Error:** Crear múltiples items `text` separados para párrafos relacionados
**Correcto:** Usar `<br>` dentro de un solo item `text` con formato `|-` (literal block)

**Ejemplo:**
```yaml
# ❌ INCORRECTO
- id: wb08
  text: "Primer párrafo"
  type: text
- id: wb09
  text: "Segundo párrafo"
  type: text

# ✅ CORRECTO
- id: wb08a
  text: |-
    Primer párrafo
    <br>Segundo párrafo
  type: text
```

### 9. **Text Container vs Text with Icon**
**Regla:**
- `text_container` con `text_content_version: disclaimer` para:
  - Listas con viñetas
  - Secciones con títulos (`### Título`)
  - Contenido estructurado

- `text_with_icon` solo para items individuales con icono específico (no para títulos de sección)

### 10. **Orden de Campos en Frontmatter**
**Error:** `page_settings_hide_on_production: false` estaba en el medio
**Correcto:** Debe estar al final, después de todos los bloques

### 11. **Texto Corregido**
**Error:** "you can form yours with now"
**Correcto:** "you can form yours with Bizee now"

## 📋 Checklist de Verificación Post-Migración

Antes de considerar una migración completa, verificar:

- [ ] UUID en formato `[slug]-001` (no UUID v4 completo)
- [ ] `seo_title: custom` cuando hay `seo_custom_meta_title`
- [ ] `seo_custom_meta_title` sin "| Bizee"
- [ ] `seo_custom_meta_description` es la descripción del hero
- [ ] Todos los links verificados contra producción (especialmente SBA)
- [ ] Hero CTA tiene parámetros UTM: `?entityType=LLC&utm_campaign={campaign}&utm_source=adwords&utm_medium=ppc`
- [ ] CTA Final es `cta_section_9` (no `cta_section_19`)
- [ ] CTA Final tiene imagen `cta-big-banner_llc.webp`
- [ ] CTA Final tiene título completo con "with Bizee"
- [ ] CTA Final link sin parámetros UTM
- [ ] FAQ tiene `list_mode: never` y `textAlign: left` en títulos/descripciones
- [ ] Contenido relacionado consolidado con `<br>` en lugar de múltiples items
- [ ] `text_container` usado para listas y secciones estructuradas
- [ ] `page_settings_hide_on_production: false` al final del frontmatter
- [ ] Todos los links del contenido principal verificados uno por uno

## 🔍 Proceso de Verificación de Links

1. Extraer TODOS los links de producción usando curl o navegador
2. Comparar uno por uno con la página migrada
3. Verificar especialmente:
   - Links de SBA (tienen URLs específicas)
   - Links internos de Bizee (pueden cambiar de `/resources/` a `/`)
   - Links a otras páginas de start-a-business
   - Links de certificación y recursos externos

## 📝 Notas Importantes

- **NUNCA** asumir que un link es correcto sin verificar contra producción
- **SIEMPRE** consolidar contenido relacionado en un solo item usando `<br>`
- **SIEMPRE** usar `text_container` para listas y contenido estructurado
- **SIEMPRE** verificar la versión correcta del CTA final (`cta_section_9` vs `cta_section_19`)
- **SIEMPRE** incluir todos los links que aparecen en producción, incluso si parecen redundantes
