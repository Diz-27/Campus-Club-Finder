<?php
// strict_types is saying we can't change the function's type
declare(strict_types=1);

class Club{
    public ?int $category_id; // this will be null until category is saved in the database
    public string $category_name;
    public ?int $club_id;
    public string $club_name;
    public string $description;
    public string $meeting_time;
    public string $meeting_location;
    public string $contact_email;

    public function __construct(
      ?int $category_id = null,
     string $category_name,
     ?int $club_id =null,
     string $club_name,
     string $description,
     string $meeting_time,
     string $meeting_location,
     string $contact_email
     )

     {
        $this->category_name = $category_name;
        $this->club_name = $club_name;
        $this->description = $description;
        $this->meeting_time = $meeting_time;
        $this->meeting_location = $meeting_location;
        $this->contact_email = $contact_email;
     }

     public static function fromRow(array $row): Club{
        return new Club(
            (int)$row['category_id'],
            $row['category_name'],
            (int)$row['club_id'],
            $row['club_name'],
            $row['description'],
            $row['meeting_time'],
            $row['meeting_location'],
            $row['contact_email']
        );
     }
}

?>