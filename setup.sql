DROP SCHEMA IF EXISTS fancystuff CASCADE;
CREATE SCHEMA fancystuff;

CREATE TABLE fancystuff.protests (

	id SERIAL NOT NULL,
	day TEXT,
	month TEXT,
	year TEXT,
	weekday TEXT,
	action TEXT,
	protester TEXT,
	state_target TEXT,
	agent TEXT,
	event TEXT,
	country TEXT,
	location TEXT,
	issue TEXT,
	time TEXT,
	no_protesters INTEGER,
	prot_arrested INTEGER,
	prot_injured INTEGER,
	prot_killed INTEGER,
	prop_damage BOOLEAN,
	no_state INTEGER,
	state_injured INTEGER,
	state_killed INTEGER
);

GRANT ALL PRIVILEGES ON SCHEMA fancystuff TO GROUP pcl3fancystuff;
