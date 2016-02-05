ALTER TABLE fancystuff.protests ADD COLUMN event_ts tsvector;
UPDATE fancystuff.protests SET event_ts = to_tsvector(event);
CREATE INDEX event_idx ON fancystuff.protests USING gin(event_ts);
