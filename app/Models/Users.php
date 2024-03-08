<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Users extends Model
{
    use HasFactory;

    protected $table = "user";
    public function getAllUsers($filters = [], $keyword = null, $sortField = null, $sortType = null, $perpage = null)
    {
        // $users = DB::select("SELECT * FROM user ORDER BY create_at DESC");
        $query = DB::table($this->table)
            ->join('groups', 'user.groups_id', '=', 'groups.id')
            ->select('user.*', 'groups.name as groups_name');

        if (!empty($filters)) {
            $query = $query->where($filters);
        }
        if (!empty($keyword)) {
            $query = $query->where(function ($queri) use ($keyword) {
                $queri->where('fullname', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }
        if (!empty($sortField) && !empty($sortType)) {
            $query = $query->orderBy($sortField, $sortType);
        } else {
            $query = $query->orderBy('create_at', 'desc');
        }

        if (!empty($perpage)) {
            $users = $query->paginate($perpage)->withQueryString();// withQueryString de giu lai query string khi chuyen trang
        } else {
            $users = $query->get();
        }

        return $users;
    }

    public function addUser($data)
    {
        // DB::insert(
        //     "INSERT INTO user (fullname, email, create_at) VALUES (?, ?, ?)",
        //     $data
        // );
        DB::table($this->table)->insert([
            'fullname' => $data[0],
            'email' => $data[1],
            'create_at' => date("Y-m-d H:i:s"),
            'groups_id' => $data[2],
            'status' => $data[3]
        ]);

    }

    public function getUserDetail($id)
    {
        $userDetail = DB::table($this->table)
            ->where('id', $id)
            ->first();

        // $userDetail = DB::select("SELECT * FROM user WHERE id = ?", [$id]);
        return $userDetail;
    }

    public function updateUser($data, $id)
    {
        // $data = array_merge($data, [$id]);
        // return DB::update(
        //     "UPDATE user SET fullname = ?, email = ?, update_at = ? WHERE id = ?",
        //     $data
        // );// nho dien dung thu tu
        return DB::table($this->table)
            ->where('id', $id)
            ->update([
                'fullname' => $data[0],
                'email' => $data[1],
                'groups_id' => $data[2],
                'status' => $data[3],
                'update_at' => $data[4]
            ]);
    }

    public function deleteUser($id)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->delete();
    }

    public function learnQueryBuilder()
    {
        $andOr = DB::table($this->table)
            ->select("fullname", "email", "create_at", "update_at") // chọn cột
            ->where([
                ["id", ">", 1],
                ["id", "<", 3]
            ])->orWhere('fullname', 'Ngọc Anh') // điều kiện And OR, lay ra id > 1 va id < 3 hoac fullname = Ngoc Anh
            ->get();

        $like = DB::table($this->table)
            ->select("fullname", "email", "create_at", "update_at")
            ->where("fullname", "like", "%Anh%") // fullname chứa Anh
            ->get();


        $whereBetween = DB::table($this->table)
            ->select("fullname", "email", "create_at", "update_at")
            ->whereBetween("id", [1, 2]) // id từ 1 đến 2
            ->get();
        $whereNotBetween = DB::table($this->table)
            ->select("fullname", "email", "create_at", "update_at")
            ->whereNotBetween("id", [1, 2]) // id không từ 1 đến 2
            ->get();


        $whereIn = DB::table($this->table)
            ->select("fullname", "email", "create_at", "update_at")
            ->whereIn("id", [1, 2, 5, 6])  // id = 1 hoặc 2 hoặc 5 hoặc 6
            ->get();
        $whereNotIn = DB::table($this->table)
            ->select("fullname", "email", "create_at", "update_at")
            ->whereNotIn("id", [1, 2, 5, 6])  // id khác 1 hoặc 2 hoặc 5 hoặc 6
            ->get();


        $whereNull = DB::table($this->table)
            ->select("fullname", "email", "create_at", "update_at")
            ->whereNull("update_at")  // update_at = null thi lay ra
            ->get();
        $whereNotNull = DB::table($this->table)
            ->select("fullname", "email", "create_at", "update_at")
            ->whereNotNull("update_at")  // update_at != null thi lay ra
            ->get();


        //Truy van Date
        $whereDate = DB::table($this->table)
            ->whereDate("create_at", "2024-03-06")  // lấy ra người dùng tạo ngày 2021-09-01
            ->get();
        $whereYear = DB::table($this->table)
            ->whereYear("create_at", "2024")  // lấy ra người dùng tạo năm 2021
            ->get();
        $whereMonth = DB::table($this->table)
            ->whereMonth("create_at", "03")  // lấy ra người dùng tạo tháng 03
            ->get();
        $whereDay = DB::table($this->table)
            ->whereDay("create_at", "06")  // lấy ra người dùng tạo ngày 06
            ->get();


        //Truy van gia tri cac cot
        $users = DB::table($this->table)
            ->whereColumn('create_at', '=', 'update_at') // so sanh 2 cot 
            ->get();


        //Join table
        $list = DB::table('user')
            ->join('groups', 'user.groups_id', '=', 'groups.id') // inner join 2 bảng
            ->select('user.*', 'groups.name as groups_name') // chọn cột muon hien thi
            ->get();

        //Khi nào cần sử dụng leftJoin, rightJoin
        //VD: Có 2 bảng liên kết với nhau: BaosTinTuc và Comment
        //Nếu sử dụng innerJoin thì chỉ lấy ra những bài viết có comment
        //Nếu sử dụng leftJoin thì lấy ra tất cả bài viết kể cả bài viết không có comment
        //Nếu sử dụng rightJoin thì lấy ra tất cả comment kể cả comment không có bài viết :))


        //Oder by
        $orderByDesc = DB::table($this->table) // sap xep giam dan
            ->orderBy('id', 'desc')
            ->get();
        $orderByAsc = DB::table($this->table) // sap xep tang dan
            ->orderBy('id', 'asc')
            ->get();
        $random = DB::table($this->table) // sap xep ngau nhien
            ->inRandomOrder()
            ->get();


        //Group by and having
        $groupBy = DB::table($this->table)
            ->select(DB::raw('count(id) as cnt'), 'email', 'fullname')
            ->groupBy('email', 'fullname') // nhung ban ghi nao co email va fullname giong nhau thi nhom lai va cnt++
            ->having('cnt', '>', 1) // lay ra nhung ban ghi co cnt > 1
            ->get();


        //limit + offset, take + skip
        $limitOffset = DB::table($this->table)
            ->offset(3) // bo qua 3 ban ghi dau tien, tuong tu skip
            ->limit(2) // lay ra toi da 2 ban ghi, tuong tu take
            ->get();


        //Insert: phai comment lai vi neu ko thi moi lan render no lai them vao db
        // DB::table($this->table)->insert([
        //     [
        //         'fullname' => 'Git Copilot',
        //         'email' => 'gitcopilot@gmail.com',
        //         'create_at' => date("Y-m-d H:i:s"),
        //         'update_at' => date("Y-m-d H:i:s"),
        //         'groups_id' => 1,
        //     ],
        //     [
        //         'fullname' => 'Git Copilot 2',
        //         'email' => 'gitcopilot2@gmail.com',
        //         'create_at' => date("Y-m-d H:i:s"),
        //         'update_at' => date("Y-m-d H:i:s"),
        //         'groups_id' => 2,
        //     ],
        // ]);

        //Update
        // DB::table($this->table)
        //     ->where('id', 1)  // Neu khong co where thi se update het bang
        //     ->update(['fullname' => 'Git Copilot 3', 'update_at' => date("Y-m-d H:i:s")]);

        //Delete
        // DB::table($this->table)
        //     ->where('id', 1)  // Neu khong co where thi se xoa het bang
        //     ->delete();

        //Dem so ban ghi
        $count = DB::table($this->table)->where('id', '>', 7)->count();

        //raw query
        $rawList = DB::table('user')
            ->where('groups_id', '=', function ($query) {
                $query->select('id')
                    ->from('groups')
                    ->where('name', '=', 'manager');
            })
            ->get();
        // dd($rawList);


    }
}
