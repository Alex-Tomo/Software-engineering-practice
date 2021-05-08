# Stores all the available regions and region codes

CREATE TABLE IF NOT EXISTS sep_regions (
    region_code varchar(16) not null unique primary key,
    region_name varchar(32) not null unique
);

INSERT INTO sep_regions VALUES ('AF', 'Afghanistan'),
                               ('AL', 'Albania'),
                               ('DZ', 'Algeria'),
                               ('AR', 'Argentina'),
                               ('AM', 'Armenia'),
                               ('AU', 'Australia'),
                               ('AT', 'Austria'),
                               ('BS', 'Bahamas'),
                               ('BH', 'Bahrain'),
                               ('BD', 'Bangladesh'),
                               ('BY', 'Belarus'),
                               ('BE', 'Belgium'),
                               ('BO', 'Bolivia'),
                               ('BR', 'Brazil'),
                               ('BG', 'Bulgaria'),
                               ('KH', 'Cambodia'),
                               ('CA', 'Canada'),
                               ('CL', 'Chile'),
                               ('CN', 'China'),
                               ('CO', 'Colombia'),
                               ('CG', 'Congo'),
                               ('CR', 'Costa Rica'),
                               ('HR', 'Croatia'),
                               ('CU', 'Cuba'),
                               ('CY', 'Cyprus'),
                               ('CZ', 'Czech Republic'),
                               ('DK', 'Denmark'),
                               ('DO', 'Dominican Republic'),
                               ('EC', 'Ecuador'),
                               ('EG', 'Egypt'),
                               ('SV', 'El Salvador'),
                               ('EE', 'Estonia'),
                               ('FJ', 'Fiji'),
                               ('FI', 'Finland'),
                               ('FR', 'France'),
                               ('DE', 'Germany'),
                               ('GI', 'Gibraltar'),
                               ('GR', 'Greece'),
                               ('GL', 'Greenland'),
                               ('HT', 'Haiti'),
                               ('HK', 'Hong Kong'),
                               ('HU', 'Hungary'),
                               ('IS', 'Iceland'),
                               ('IN', 'India'),
                               ('ID', 'Indonesia'),
                               ('IR', 'Iran'),
                               ('IQ', 'Iraq'),
                               ('IE', 'Ireland'),
                               ('IL', 'Israel'),
                               ('IT', 'Italy'),
                               ('JM', 'Jamaica'),
                               ('JP', 'Japan'),
                               ('JO', 'Jordan'),
                               ('KZ', 'Kazakstan'),
                               ('KE', 'Kenya'),
                               ('KR', 'Korea'),
                               ('KW', 'Kuwait'),
                               ('LV', 'Latvia'),
                               ('LB', 'Lebanon'),
                               ('LT', 'Lithuania'),
                               ('LU', 'Luxembourg'),
                               ('MY', 'Malaysia'),
                               ('MT', 'Malta'),
                               ('MX', 'Mexico'),
                               ('MC', 'Monaco'),
                               ('MN', 'Mongolia'),
                               ('MA', 'Morocco'),
                               ('NP', 'Nepal'),
                               ('NL', 'Netherlands'),
                               ('NZ', 'New Zealand'),
                               ('NI', 'Nicaragua'),
                               ('NO', 'Norway'),
                               ('PK', 'Pakistan'),
                               ('PH', 'Philippines'),
                               ('PL', 'Poland'),
                               ('PT', 'Portugal'),
                               ('PR', 'Puerto Rico'),
                               ('RO', 'Romania'),
                               ('RU', 'Russia'),
                               ('SA', 'Saudi Arabia'),
                               ('RS', 'Serbia'),
                               ('SG', 'Singapore'),
                               ('SK', 'Slovakia'),
                               ('ES', 'Spain'),
                               ('LK', 'Sri Lanka'),
                               ('SE', 'Sweden'),
                               ('CH', 'Switzerland'),
                               ('TH', 'Thailand'),
                               ('TN', 'Tunisia'),
                               ('TR', 'Turkey'),
                               ('UA', 'Ukraine'),
                               ('AE', 'United Arab Emirates'),
                               ('GB', 'United Kingdom'),
                               ('US', 'United States'),
                               ('UY', 'Uruguay'),
                               ('VE', 'Venezuela'),
                               ('YE', 'Yemen');