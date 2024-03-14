<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    use HasFactory;

    public function owner()
    {
        // Tại sao lại sử dụng hasOneThrough ở Model Mechanic?
        // Flow: Mechanic -> Car -> CarOwner
        // Có thể hiểu: CarOwner sở hữu foreign key của Car, Car sở hữu foreign key của Mechanic.
        // Ở đây, Mechanic có thể coi là phần tử bé nhất, nên phải truy vấn từ nó.
        // Vì nếu ko có thằng bé nhất thì ko có thằng trung gian, ko có thằng trung gian thì ko có thằng đích.
        // Nếu muốn dùng hasOneThrough ở Model CarOwner thì phải thay đổi cấu trúc database, để carowner là tk bé nhất.
        return $this->hasOneThrough(
            CarOwner::class, // Model đích
            Car::class, // Model trung gian
            'mechanic_id', // Khóa ngoại bảng trung gian
            'car_id', // Khóa ngoại bảng đích
            'id', // Khóa chính bảng hiện tại
            'id' // khóa chính bảng trung gian
        );

    }
}
