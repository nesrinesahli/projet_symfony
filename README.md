# **Dentary - Dental Management Platform**

Dentary is a web-based **dental management platform** built using Symfony and MySQL. The platform supports two types of users:  
1. **Patients**: Manage their rendezvous (appointments) and view their appointment history.  
2. **Professionals**: Accept, modify, create, and manage appointments for their patients.  

---

## **Features**

### For Patients:
- **View Rendezvous**: Patients can view their appointment history.
- **Modify Rendezvous**: Patients can edit or delete appointments with a status of `scheduled`.
- **Create Rendezvous**: Submit new appointments by choosing a professional and specifying the required date and time.

### For Professionals:
- **Manage Appointments**: Accept, modify, or delete patient appointments.
- **Create Appointments**: Manually create appointments for their patients.
- **Availability Management**: Set available dates and times for scheduling.

---

## **Technology Stack**

- **Framework**: Symfony (PHP 8+)
- **Database**: MySQL
- **Front-end**: Twig templates, Bootstrap (CSS Framework)
- **Dependencies**: Doctrine ORM, Symfony Security, and Symfony Forms
- **Tools**: Composer, Symfony CLI

---

## **Database Schema**

The project includes **7 tables** in the `dentary_db` database:

### **Tables Overview**

1. **user**
   | Field    | Type         | Key   | Extra          |
   |----------|--------------|-------|----------------|
   | id       | int          | PRI   | auto_increment |
   | email    | varchar(100) | UNI   |                |
   | password | varchar(255) |       |                |
   | role     | varchar(20)  |       |                |

2. **patient**
   | Field           | Type         | Key   | Extra          |
   |-----------------|--------------|-------|----------------|
   | id              | int          | PRI   | auto_increment |
   | user_id         | int          | UNI   |                |
   | first_name      | varchar(50)  |       |                |
   | last_name       | varchar(50)  |       |                |
   | gender          | varchar(10)  |       |                |
   | date_of_birth   | date         |       |                |
   | chronic_disease | tinyint(1)   |       |                |
   | phone_number    | varchar(20)  |       |                |
   | address         | longtext     |       |                |
   | created_at      | datetime     |       |                |

3. **professionals**
   | Field           | Type         | Key   | Extra          |
   |-----------------|--------------|-------|----------------|
   | id              | int          | PRI   | auto_increment |
   | user_id         | int          | UNI   |                |
   | first_name      | varchar(50)  |       |                |
   | last_name       | varchar(50)  |       |                |
   | cin_card_number | varchar(20)  | UNI   |                |
   | specialty       | varchar(100) |       |                |
   | phone_number    | varchar(20)  |       |                |
   | description     | longtext     |       |                |
   | created_at      | datetime     |       |                |
   | address         | longtext     |       |                |

4. **rendezvous**
   | Field            | Type         | Key   | Extra               |
   |------------------|--------------|-------|---------------------|
   | id               | int          | PRI   | auto_increment      |
   | patient_id       | int          | MUL   |                     |
   | professional_id  | int          | MUL   |                     |
   | appointment_date | datetime     |       |                     |
   | status           | varchar(20)  |       | Default: scheduled  |
   | notes            | longtext     |       |                     |
   | created_at       | datetime     |       | CURRENT_TIMESTAMP   |

5. **availability**
   | Field           | Type         | Key   | Extra          |
   |-----------------|--------------|-------|----------------|
   | id              | int          | PRI   | auto_increment |
   | professional_id | int          | MUL   |                |
   | available_date  | date         |       |                |
   | available_time  | time         |       |                |

6. **doctrine_migration_versions**  
   (Symfony table for managing database migrations)

7. **messenger_messages**  
   (Symfony table for handling queued messages)

---

## **Installation**

Follow these steps to set up the project locally:

### Prerequisites
- PHP 8+  
- Composer  
- Symfony CLI  
- MySQL  

---

### Steps

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/yourusername/dentary.git
   cd dentary
2. **Install Dependencies:**
   ```bash
   composer install
3. **Configure Environment Variables:**
   ```bash
   Duplicate the .env file and update database credentials: DATABASE_URL="mysql://username:password@127.0.0.1:3306/dentary_db"
4.**Create the Database then start symfony project:**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   symfony server:start

