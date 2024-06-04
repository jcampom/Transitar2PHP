ALTER TABLE recaudos 
ALTER COLUMN telefono_pagador VARCHAR(50) NOT NULL;

ALTER TABLE recaudos ADD DEFAULT ' ' FOR direccion_pagador;