
ALTER TABLE recenzni_rizeni 
ADD CONSTRAINT recenzni_rizeni_constr 
FOREIGN KEY (tisk_id) 
REFERENCES tisk(id) ON DELETE CASCADE;


ALTER TABLE clanek
ADD CONSTRAINT clanek_constr 
FOREIGN KEY (recenzni_rizeni_id) 
REFERENCES recenzni_rizeni(id) ON DELETE CASCADE;


ALTER TABLE namitka
ADD CONSTRAINT namitka_constr 
FOREIGN KEY (clanek_id) 
REFERENCES clanek(id) ON DELETE CASCADE;


ALTER TABLE posudek
ADD CONSTRAINT posudek_constr 
FOREIGN KEY (clanek_id) 
REFERENCES clanek(id) ON DELETE CASCADE;


ALTER TABLE verze_clanku 
ADD CONSTRAINT verze_clanku_constr 
FOREIGN KEY (clanek_id) 
REFERENCES clanek(id) ON DELETE CASCADE;


ALTER TABLE komentar_clanek 
ADD CONSTRAINT komentar_clanek_constr 
FOREIGN KEY (verze_clanku_id) 
REFERENCES verze_clanku(id) ON DELETE CASCADE;


ALTER TABLE ukol
ADD CONSTRAINT ukol_constr 
FOREIGN KEY (clanek_id) 
REFERENCES clanek(id) ON DELETE CASCADE;


ALTER TABLE komentar_ukol
ADD CONSTRAINT komentar_ukol_constr 
FOREIGN KEY (verze_clanku_id) 
REFERENCES verze_clanku(id) ON DELETE CASCADE;




