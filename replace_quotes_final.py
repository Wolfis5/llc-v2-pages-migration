#!/usr/bin/env python3
import re
import sys

# Verificar que se proporcionó un argumento
if len(sys.argv) < 2:
    print("Uso: python3 replace_quotes_final.py <ruta_al_archivo>")
    print("Ejemplo: python3 replace_quotes_final.py content/collections/start_a_business_pages/side-hustle.md")
    sys.exit(1)

file_path = sys.argv[1]

# Leer el archivo completo
with open(file_path, 'r', encoding='utf-8') as f:
    lines = f.readlines()

# Campos que pueden contener texto
text_fields = ['text', 'description', 'label', 'title', 'mobile_label', 'button_label', 'label_mobile']

processed_lines = []
changes = 0

for line_num, line in enumerate(lines, 1):
    original_line = line
    line_stripped = line.rstrip('\n\r')
    new_line = line

    for field in text_fields:
        # Buscar líneas que empiecen con el campo
        pattern = rf"^(\s*{field}:\s*)"
        match = re.match(pattern, line_stripped)

        if match:
            prefix = match.group(1)
            rest = line_stripped[len(prefix):]

            # Si empieza con comilla simple
            if rest.startswith("'") and rest.count("'") >= 2:
                # Encontrar primera y última comilla
                first_idx = 0
                last_idx = len(rest) - 1
                # Buscar la última comilla desde el final
                for i in range(len(rest) - 1, -1, -1):
                    if rest[i] == "'":
                        last_idx = i
                        break

                if first_idx < last_idx:
                    # Extraer contenido
                    content = rest[first_idx + 1:last_idx]
                    # Reemplazar comillas simples por tipográficas (U+2019)
                    typographic_quote = '\u2019'  # Comilla tipográfica derecha
                    new_content = content.replace("'", typographic_quote)

                    if new_content != content:
                        # Reconstruir línea
                        new_line_stripped = prefix + "'" + new_content + "'" + rest[last_idx + 1:]
                        # Restaurar salto de línea
                        if line.endswith('\r\n'):
                            new_line = new_line_stripped + '\r\n'
                        elif line.endswith('\n'):
                            new_line = new_line_stripped + '\n'
                        elif line.endswith('\r'):
                            new_line = new_line_stripped + '\r'
                        else:
                            new_line = new_line_stripped
                        changes += 1
                        if changes <= 3:
                            print(f"Línea {line_num} modificada")
                        break

            # Si empieza con comilla doble
            elif rest.startswith('"') and rest.count('"') >= 2:
                first_idx = 0
                last_idx = len(rest) - 1
                for i in range(len(rest) - 1, -1, -1):
                    if rest[i] == '"':
                        last_idx = i
                        break

                if first_idx < last_idx:
                    content = rest[first_idx + 1:last_idx]
                    typographic_quote = '\u2019'  # Comilla tipográfica derecha
                    new_content = content.replace("'", typographic_quote)

                    if new_content != content:
                        new_line_stripped = prefix + '"' + new_content + '"' + rest[last_idx + 1:]
                        if line.endswith('\r\n'):
                            new_line = new_line_stripped + '\r\n'
                        elif line.endswith('\n'):
                            new_line = new_line_stripped + '\n'
                        elif line.endswith('\r'):
                            new_line = new_line_stripped + '\r'
                        else:
                            new_line = new_line_stripped
                        changes += 1
                        if changes <= 3:
                            print(f"Línea {line_num} modificada")
                        break

    processed_lines.append(new_line)

# Guardar el archivo
with open(file_path, 'w', encoding='utf-8') as f:
    f.writelines(processed_lines)

print(f'Procesamiento completado. {changes} líneas modificadas.')
