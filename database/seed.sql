-- Datos iniciales de demostración
INSERT INTO accounts (name, industry, email, phone, website, city, status, owner)
SELECT 'Northstar Retail', 'Retail', 'contacto@northstar.example', '+51 1 640 2200', 'https://northstar.example', 'Lima', 'Activa', 'María Salazar'
WHERE NOT EXISTS (SELECT 1 FROM accounts WHERE name = 'Northstar Retail');

INSERT INTO accounts (name, industry, email, phone, website, city, status, owner)
SELECT 'Andes Financial', 'Finanzas', 'negocios@andesfinancial.example', '+51 1 410 1800', 'https://andesfinancial.example', 'Lima', 'Prospecto', 'Javier Rojas'
WHERE NOT EXISTS (SELECT 1 FROM accounts WHERE name = 'Andes Financial');

INSERT INTO accounts (name, industry, email, phone, website, city, status, owner)
SELECT 'Pacífico Health', 'Salud', 'administracion@pacificohealth.example', '+51 54 380 900', 'https://pacificohealth.example', 'Arequipa', 'Activa', 'Lucía Vega'
WHERE NOT EXISTS (SELECT 1 FROM accounts WHERE name = 'Pacífico Health');

INSERT INTO contacts (account_id, first_name, last_name, job_title, email, phone, status)
SELECT a.id, 'Carlos', 'Mendoza', 'Director de Operaciones', 'carlos.mendoza@northstar.example', '+51 999 240 118', 'Activo'
FROM accounts a WHERE a.name = 'Northstar Retail'
AND NOT EXISTS (SELECT 1 FROM contacts WHERE email = 'carlos.mendoza@northstar.example');

INSERT INTO contacts (account_id, first_name, last_name, job_title, email, phone, status)
SELECT a.id, 'Andrea', 'Paredes', 'Gerente de Transformación', 'andrea.paredes@andesfinancial.example', '+51 987 300 441', 'Potencial'
FROM accounts a WHERE a.name = 'Andes Financial'
AND NOT EXISTS (SELECT 1 FROM contacts WHERE email = 'andrea.paredes@andesfinancial.example');

INSERT INTO contacts (account_id, first_name, last_name, job_title, email, phone, status)
SELECT a.id, 'Diego', 'Valdivia', 'Jefe de Tecnología', 'diego.valdivia@pacificohealth.example', '+51 956 110 782', 'Activo'
FROM accounts a WHERE a.name = 'Pacífico Health'
AND NOT EXISTS (SELECT 1 FROM contacts WHERE email = 'diego.valdivia@pacificohealth.example');

INSERT INTO opportunities (account_id, name, stage, amount, probability, expected_close, owner, notes)
SELECT a.id, 'Optimización de operaciones 2026', 'Propuesta', 128000, 65, CURRENT_DATE + 45, 'María Salazar', 'Propuesta funcional y económica enviada.'
FROM accounts a WHERE a.name = 'Northstar Retail'
AND NOT EXISTS (SELECT 1 FROM opportunities WHERE name = 'Optimización de operaciones 2026');

INSERT INTO opportunities (account_id, name, stage, amount, probability, expected_close, owner, notes)
SELECT a.id, 'Automatización de atención', 'Negociación', 84500, 80, CURRENT_DATE + 28, 'Javier Rojas', 'Revisión contractual en curso.'
FROM accounts a WHERE a.name = 'Andes Financial'
AND NOT EXISTS (SELECT 1 FROM opportunities WHERE name = 'Automatización de atención');

INSERT INTO opportunities (account_id, name, stage, amount, probability, expected_close, owner, notes)
SELECT a.id, 'Mesa de servicios administrados', 'Calificación', 56700, 40, CURRENT_DATE + 70, 'Lucía Vega', 'Pendiente validación de alcance.'
FROM accounts a WHERE a.name = 'Pacífico Health'
AND NOT EXISTS (SELECT 1 FROM opportunities WHERE name = 'Mesa de servicios administrados');

INSERT INTO commitments (account_id, contact_id, opportunity_id, title, description, due_date, priority, status, assigned_to)
SELECT a.id, c.id, o.id, 'Entregar versión final de la propuesta', 'Incluir cronograma, niveles de servicio y plan de transición.', CURRENT_DATE + 5, 'Alta', 'En curso', 'María Salazar'
FROM accounts a
JOIN contacts c ON c.account_id = a.id
JOIN opportunities o ON o.account_id = a.id
WHERE a.name = 'Northstar Retail'
AND NOT EXISTS (SELECT 1 FROM commitments WHERE title = 'Entregar versión final de la propuesta');

INSERT INTO commitments (account_id, contact_id, opportunity_id, title, description, due_date, priority, status, assigned_to)
SELECT a.id, c.id, o.id, 'Validar condiciones contractuales', 'Coordinar la respuesta con asesoría legal.', CURRENT_DATE + 9, 'Media', 'Pendiente', 'Javier Rojas'
FROM accounts a
JOIN contacts c ON c.account_id = a.id
JOIN opportunities o ON o.account_id = a.id
WHERE a.name = 'Andes Financial'
AND NOT EXISTS (SELECT 1 FROM commitments WHERE title = 'Validar condiciones contractuales');

INSERT INTO activities (account_id, contact_id, opportunity_id, commitment_id, type, subject, description, scheduled_at, duration_minutes, status, assigned_to)
SELECT a.id, c.id, o.id, cm.id, 'Reunión', 'Revisión ejecutiva de propuesta', 'Presentación de alcance y próximos acuerdos.', NOW() + INTERVAL '3 days', 60, 'Programada', 'María Salazar'
FROM accounts a
JOIN contacts c ON c.account_id = a.id
JOIN opportunities o ON o.account_id = a.id
JOIN commitments cm ON cm.opportunity_id = o.id
WHERE a.name = 'Northstar Retail'
AND NOT EXISTS (SELECT 1 FROM activities WHERE subject = 'Revisión ejecutiva de propuesta');

INSERT INTO activities (account_id, contact_id, opportunity_id, type, subject, description, scheduled_at, duration_minutes, status, assigned_to)
SELECT a.id, c.id, o.id, 'Llamada', 'Seguimiento de revisión contractual', 'Confirmar observaciones y fecha de decisión.', NOW() + INTERVAL '5 days', 30, 'Programada', 'Javier Rojas'
FROM accounts a
JOIN contacts c ON c.account_id = a.id
JOIN opportunities o ON o.account_id = a.id
WHERE a.name = 'Andes Financial'
AND NOT EXISTS (SELECT 1 FROM activities WHERE subject = 'Seguimiento de revisión contractual');
