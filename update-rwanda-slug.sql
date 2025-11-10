-- Update Rwanda's slug from 'rwanda' to 'visit-rw'
-- This will make Rwanda redirect to http://visit-rw.foreveryoungtours.local

UPDATE countries SET slug = 'visit-rw' WHERE name = 'Rwanda';

-- Verify the change
SELECT id, name, slug FROM countries WHERE name = 'Rwanda';
