CREATE TABLE `administering` (
     `administering_id` int(11) NOT NULL auto_increment primary key ,
     `patient_name` varchar(100) NOT NULL,
     `phone` varchar(15) NOT NULL,
     `user_id` int(11) NOT NULL,
     `message` text NOT NULL,
     `alert_status` tinyint(1) NOT NULL DEFAULT 1,
     `duration_date` date NOT NULL,
     `date` datetime NOT NULL DEFAULT current_timestamp()
);

CREATE TABLE `administer_drugs` (
    `administer_drugs_id` int(11) NOT NULL auto_increment primary key ,
    `administering_id` int(11) NOT NULL,
    `drug_name` varchar(100) NOT NULL,
    `qty` int(11) NOT NULL,
    `dosage` int(11) NOT NULL,
    `times` bit(2) NOT NULL
);

CREATE TABLE `drug_tb` (
   `drug_id` int(11) NOT NULL auto_increment primary key ,
   `name` varchar(255) NOT NULL,
   `category` varchar(100) NOT NULL,
   `prescription` longtext NOT NULL,
   `date` datetime NOT NULL DEFAULT current_timestamp()
);

INSERT INTO `drug_tb` (`drug_id`, `name`, `category`, `prescription`, `date`) VALUES
      (1, 'Paracetamol', 'Tablet', 'Paracetamol is often recommended as the first medicine to try, if you have short-term pain', '2024-07-01 03:22:07'),
      (2, 'Indomethacin', 'Tablet', 'Indomethacin: Immediate release: 25 to 50 mg every 8 to 12 hours. Controlled release: 75 mg once or twice daily', '2024-07-01 03:23:31'),
      (3, 'Amitriptyline', 'Capsule', 'Antidepressant medications: Among the tricyclic antidepressants and selective serotonin and norepinephrine reuptake inhibitors', '2024-07-01 03:24:31'),
      (4, 'Opioid', 'Capsule', 'Opioids are a class of drugs that derive from, or mimic, natural substances found in the opium poppy plant', '2024-07-01 03:25:31'),
      (5, 'Aspirin', 'Tablet', 'Aspirin, also known as acetylsalicylic acid, is a nonsteroidal anti-inflammatory drug used to reduce pain, fever', '2024-07-01 03:26:11'),
      (6, 'Naproxen', 'Tablet', 'Naproxen, sold under the brand name Aleve among others, is a nonsteroidal anti-inflammatory drug used to treat pain', '2024-07-01 03:26:47'),
      (7, 'Opioids', 'Capsule', 'Opioids work in the brain to produce a variety of effects, including pain relief', '2024-07-01 03:27:25'),
      (8, 'Diclofenac', 'Capsule', 'Diclofenac, sold under the brand name Voltaren, among others, is a nonsteroidal anti-inflammatory drug used to treat pain and inflammatory diseases such as gout', '2024-07-01 03:28:05');

CREATE TABLE `users_tb` (
    `user_id` int(11) NOT NULL auto_increment primary key ,
    `username` varchar(100) NOT NULL,
    `name` varchar(255) NOT NULL,
    `contact` varchar(15) NOT NULL,
    `password` varchar(100) NOT NULL,
    `role` int(1) NOT NULL,
    `code` varchar(100) DEFAULT NULL,
    `date` datetime NOT NULL DEFAULT current_timestamp()
);


INSERT INTO `users_tb` (`username`, `name`, `contact`, `password`, `role`, `code`, `date`) VALUES
    ('deluke', 'Lukeman Dramani', '0559574121', '$2y$10$c/anX2h3IAU0SqjaVH2u0eSHMoTSM6vXZRpNhbn8TTTB3uyfgDk4q', 2, NULL, '2024-06-04 11:31:20');
