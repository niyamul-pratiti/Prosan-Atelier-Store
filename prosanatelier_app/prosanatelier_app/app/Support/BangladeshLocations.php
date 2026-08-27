<?php

namespace App\Support;

final class BangladeshLocations
{
    /** Dhaka district areas that use the configurable suburban delivery rate. */
    private const DHAKA_SUBURBAN_AREAS = [
        'Ashulia',
        'Dhamrai',
        'Dohar',
        'Hemayetpur',
        'Keraniganj Model',
        'Nawabganj',
        'Savar',
        'South Keraniganj',
    ];

    /** District-wise delivery areas (upazilas and metropolitan thanas). */
    private const DISTRICT_AREAS = [
        'Bagerhat' => ['Bagerhat Sadar', 'Chitalmari', 'Fakirhat', 'Kachua', 'Mollahat', 'Mongla', 'Morrelganj', 'Rampal', 'Sarankhola', 'Other / Not listed'],
        'Bandarban' => ['Ali Kadam', 'Bandarban Sadar', 'Lama', 'Naikhongchhari', 'Rowangchhari', 'Ruma', 'Thanchi', 'Other / Not listed'],
        'Barguna' => ['Amtali', 'Bamna', 'Barguna Sadar', 'Betagi', 'Patharghata', 'Taltoli', 'Other / Not listed'],
        'Barishal' => ['Agailjhara', 'Airport', 'Babuganj', 'Bakerganj', 'Banaripara', 'Bandara', 'Barial', 'Barishal Sadar', 'Char Aila Kathi', 'Gournadi', 'Hijla', 'Kashipur', 'Kotwali', 'Mehendiganj', 'Muladi', 'Rupatali', 'Sadar', 'Uzirpur', 'Other / Not listed'],
        'Bhola' => ['Bhola Sadar', 'Borhanuddin', 'Char Fasson', 'Daulatkhan', 'Lalmohan', 'Monpura', 'Tazumuddin', 'Other / Not listed'],
        'Bogura' => ['Adamdighi', 'Bogura Sadar', 'Dhunat', 'Dupchanchia', 'Gabtali', 'Kahaloo', 'Nandigram', 'Sariakandi', 'Shajahanpur', 'Sherpur', 'Shibganj', 'Sonatala', 'Other / Not listed'],
        'Brahmanbaria' => ['Akhaura', 'Ashuganj', 'Bancharampur', 'Bijoynagar', 'Brahmanbaria Sadar', 'Kasba', 'Nabinagar', 'Nasirnagar', 'Sarail', 'Other / Not listed'],
        'Chandpur' => ['Chandpur Sadar', 'Faridganj', 'Haimchar', 'Hajiganj', 'Kachua', 'Matlab Dakkhin', 'Matlab Uttar', 'Shahrasti', 'Other / Not listed'],
        'Chapai Nawabganj' => ['Bholahat', 'Chapai Nawabganj Sadar', 'Gomastapur', 'Nachol', 'Shibganj', 'Other / Not listed'],
        'Chattogram' => ['Akbarshah', 'Anwara', 'Bakalia', 'Bandar', 'Banshkhali', 'Bayazid', 'Boalkhali', 'Chandanaish', 'Chandgaon', 'Chawkbazar', 'Double Mooring', 'EPZ', 'Fatikchhari', 'Halishahar', 'Hathazari', 'Karnaphuli', 'Khulshi', 'Kotwali', 'Lohagara', 'Mirsarai', 'Mirsharai', 'Pahartali', 'Panchlaish', 'Patenga', 'Patiya', 'Rangunia', 'Raozan', 'Rauzan', 'Sadarghat', 'Sandwip', 'Satkania', 'Sitakund', 'Sitakunda', 'Other / Not listed'],
        'Chuadanga' => ['Alamdanga', 'Chuadanga Sadar', 'Damurhuda', 'Jibannagar', 'Other / Not listed'],
        'Cox\'s Bazar' => ['Chakaria', 'Cox\'s Bazar Sadar', 'Eidgaon', 'Kutubdia', 'Maheshkhali', 'Pekua', 'Ramu', 'Teknaf', 'Ukhia', 'Ukhiya', 'Other / Not listed'],
        'Cumilla' => ['Bangrabazar', 'Barura', 'Brahmanpara', 'Burichong', 'Chandina', 'Chowddagram', 'Cumilla Sadar', 'Cumilla Sadar South', 'Daudkandi', 'Debidwar', 'Homna', 'Laksam', 'Lalmai', 'Meghna', 'Monohorgonj', 'Muradnagar', 'Nangalkot', 'Sadar Dakshin', 'Titas', 'Other / Not listed'],
        'Dhaka' => ['Adabor', 'Airport', 'Ashulia', 'Badda', 'Banani', 'Bangshal', 'Bhashantek', 'Cantonment', 'Chawkbazar', 'Dakshin Khan', 'Darus-Salam', 'Demra', 'Dhamrai', 'Dhanmondi', 'Dohar', 'Gandaria', 'Gulshan', 'Hatirjheel', 'Hazaribagh', 'Hemayetpur', 'Jatrabari', 'Kadamtoli', 'Kafrul', 'Kalabagan', 'Kamrangirchar', 'Keraniganj', 'Keraniganj Model', 'Khilgaon', 'Khilkhet', 'Kotwali', 'Lalbagh', 'Mirpur Model', 'Mohammadpur', 'Motijheel', 'Mugda', 'Nawabganj', 'New Market', 'Pallabi', 'Paltan Model', 'Ramna Model', 'Rampura', 'Rupnagar', 'Sabujbagh', 'Savar', 'Shah Ali', 'Shahbagh', 'Shahjahanpur', 'Sher-e-Bangla Nagar', 'Shyampur', 'South Keraniganj', 'Sutrapur', 'Tejgaon', 'Tejgaon Industrial', 'Turag', 'Uttar Khan', 'Uttara East', 'Uttara West', 'Vatara', 'Wari', 'Other / Not listed'],
        'Dinajpur' => ['Birampur', 'Birganj', 'Birol', 'Bochaganj', 'Chirirbandar', 'Dinajpur Sadar', 'Ghoraghat', 'Hakimpur', 'Kaharol', 'Khansama', 'Nawabganj', 'Parbatipur', 'Phulbari', 'Other / Not listed'],
        'Faridpur' => ['Alfadanga', 'Bhanga', 'Boalmari', 'Charbhadrasan', 'Faridpur Sadar', 'Madhukhali', 'Nagarkanda', 'Sadarpur', 'Saltha', 'Other / Not listed'],
        'Feni' => ['Chhagalnaiya', 'Daganbhuiyan', 'Feni Sadar', 'Parshuram', 'Phulgazi', 'Sonagazi', 'Other / Not listed'],
        'Gaibandha' => ['Gaibandha Sadar', 'Gobindaganj', 'Phulchhari', 'Polashbari', 'Sadullapur', 'Saghata', 'Sundarganj', 'Other / Not listed'],
        'Gazipur' => ['Bason', 'Gacha', 'Gazipur Sadar', 'Joydebpur', 'Kaliakair', 'Kaliganj', 'Kapasia', 'Kashimpur', 'Konabari', 'Pubail', 'Sreepur', 'Tongi East', 'Tongi West', 'Other / Not listed'],
        'Gopalganj' => ['Gopalganj Sadar', 'Kashiani', 'Kotalipara', 'Muksudpur', 'Tungipara', 'Other / Not listed'],
        'Habiganj' => ['Azmiriganj', 'Bahubal', 'Baniachang', 'Chunarughat', 'Habiganj Sadar', 'Lakhai', 'Madhabpur', 'Nabiganj', 'Shaistaganj', 'Other / Not listed'],
        'Jamalpur' => ['Bakshiganj', 'Dewanganj', 'Islampur', 'Jamalpur Sadar', 'Madariganj', 'Melandaha', 'Sarisabari', 'Other / Not listed'],
        'Jashore' => ['Abhaynagar', 'Bagharpara', 'Chowgacha', 'Jashore Sadar', 'Jhikargacha', 'Keshabpur', 'Monirampur', 'Sharsha', 'Other / Not listed'],
        'Jhalokati' => ['Jhalokathi Sadar', 'Kathalia', 'Nalchity', 'Rajapur', 'Other / Not listed'],
        'Jhenaidah' => ['Harinakundu', 'Jhenaidah Sadar', 'Kaliganj', 'Kotchandpur', 'Moheshpur', 'Shailkupa', 'Other / Not listed'],
        'Joypurhat' => ['Akkelpur', 'Joypurhat Sadar', 'Kalai', 'Khetlal', 'Panchbibi', 'Other / Not listed'],
        'Khagrachhari' => ['Dighinala', 'Guimara', 'Khagrachhari Sadar', 'Lakshichhari', 'Mahalchhari', 'Manikchhari', 'Matiranga', 'Panchhari', 'Ramgarh', 'Other / Not listed'],
        'Khulna' => ['Aranghata', 'Batiaghata', 'Dacope', 'Daulatpur', 'Dighalia', 'Dumuria', 'Horintana', 'Khalishpur', 'Khan Jahan Ali', 'Kotwali', 'Koyra', 'Labanchara', 'Paikgachha', 'Phultala', 'Rupsa', 'Sonadanga', 'Terokhada', 'Other / Not listed'],
        'Kishoreganj' => ['Astagram', 'Bajitpur', 'Hossainpur', 'Itna', 'Karimganj', 'Katiadi', 'Kishoreganj Sadar', 'Kuliarchar', 'Mithamain', 'Nikli', 'Pakundia', 'Tarail', 'Vairab', 'Other / Not listed'],
        'Kurigram' => ['Bhurungamari', 'Char Rajibpur', 'Chilmari', 'Kurigram Sadar', 'Nageswari', 'Phulbari', 'Rajarhat', 'Roumari', 'Ulipur', 'Other / Not listed'],
        'Kushtia' => ['Bheramara', 'Daulatpur', 'Khoksa', 'Kumarkhali', 'Kushtia Sadar', 'Mirpur', 'Other / Not listed'],
        'Lakshmipur' => ['Kamalnagar', 'Lakshmipur Sadar', 'Raipur', 'Ramganj', 'Ramgati', 'Other / Not listed'],
        'Lalmonirhat' => ['Aditmari', 'Hatibandha', 'Kaliganj', 'Lalmonirhat Sadar', 'Patgram', 'Other / Not listed'],
        'Madaripur' => ['Dasar', 'Kalkini', 'Madaripur Sadar', 'Rajoir', 'Shibchar', 'Other / Not listed'],
        'Magura' => ['Magura Sadar', 'Mohammadpur', 'Shalikha', 'Sreepur', 'Other / Not listed'],
        'Manikganj' => ['Daulatpur', 'Ghior', 'Harirampur', 'Manikganj Sadar', 'Saturia', 'Shivalaya', 'Singair', 'Other / Not listed'],
        'Meherpur' => ['Gangni', 'Meherpur Sadar', 'Mujibnagar', 'Other / Not listed'],
        'Moulvibazar' => ['Bara Lekha', 'Juri', 'Kamalganj', 'Kulaura', 'Moulvibazar Sadar', 'Rajnagar', 'Sreemangal', 'Other / Not listed'],
        'Munshiganj' => ['Gazaria', 'Lohajang', 'Munshiganj Sadar', 'Sirajdikhan', 'Sreenagar', 'Tongibari', 'Other / Not listed'],
        'Mymensingh' => ['Bhaluka', 'Dhoubaura', 'Fulbaria', 'Gafargaon', 'Gouripur', 'Haluaghat', 'Ishwarganj', 'Kotwali', 'Muktagachha', 'Mymensingh Sadar', 'Nandail', 'Phulpur', 'Sadar', 'Tarakanda', 'Trishal', 'Other / Not listed'],
        'Naogaon' => ['Atrai', 'Badalgachi', 'Dhamoirhat', 'Manda', 'Mohadevpur', 'Naogaon Sadar', 'Niamatpur', 'Patnitala', 'Porsha', 'Raninagar', 'Sapahar', 'Other / Not listed'],
        'Narail' => ['Kalia', 'Lohagara', 'Narail Sadar', 'Other / Not listed'],
        'Narayanganj' => ['Araihazar', 'Bandar', 'Fatullah', 'Narayanganj Sadar', 'Rupganj', 'Sonargaon', 'Other / Not listed'],
        'Narsingdi' => ['Belabo', 'Monohardi', 'Narsingdi Sadar', 'Palash', 'Raipura', 'Shibpur', 'Other / Not listed'],
        'Natore' => ['Bagatipara', 'Baraigram', 'Gurudaspur', 'Lalpur', 'Naldanga', 'Natore Sadar', 'Singra', 'Other / Not listed'],
        'Netrokona' => ['Atpara', 'Barhatta', 'Durgapur', 'Kendua', 'Khaliajuri', 'Khalmakanda', 'Madan', 'Mohanganj', 'Netrokona Sadar', 'Purbadhala', 'Other / Not listed'],
        'Nilphamari' => ['Dimla', 'Domar', 'Jaldhaka', 'Kishoreganj', 'Nilphamari Sadar', 'Saidpur', 'Other / Not listed'],
        'Noakhali' => ['Begumganj', 'Chatkhil', 'Companiganj', 'Hatia', 'Kabirhat', 'Noakhali Sadar', 'Senbagh', 'Sonaimuri', 'Subarnachar', 'Other / Not listed'],
        'Pabna' => ['Atgharia', 'Bera', 'Bhangura', 'Chatmohor', 'Faridpur', 'Ishwardi', 'Pabna Sadar', 'Santhia', 'Sujanagar', 'Other / Not listed'],
        'Panchagarh' => ['Atwari', 'Boda', 'Debiganj', 'Panchagarh Sadar', 'Tetulia', 'Other / Not listed'],
        'Patuakhali' => ['Bauphal', 'Dashmina', 'Dumki', 'Galachipa', 'Kalapara', 'Mirzaganj', 'Patuakhali Sadar', 'Rangabali', 'Other / Not listed'],
        'Pirojpur' => ['Bhandaria', 'Indurkani', 'Kaukhali', 'Mathbaria', 'Nazirpur', 'Nesarabad', 'Pirojpur Sadar', 'Other / Not listed'],
        'Rajbari' => ['Baliakandi', 'Goalanda', 'Kalukhali', 'Pangsha', 'Rajbari Sadar', 'Other / Not listed'],
        'Rajshahi' => ['Bagha', 'Bagmara', 'Boalia Model', 'Charghat', 'Durgapur', 'Godagari', 'Mohonpur', 'Motihar', 'Paba', 'Puthia', 'Rajpara', 'Shah Makhdum', 'Tanore', 'Other / Not listed'],
        'Rangamati' => ['Baghaichhari', 'Barkal', 'Bilaichhari', 'Juraichhari', 'Kaptai', 'Kaukhali', 'Langadu', 'Naniarchar', 'Rajasthali', 'Rangamati Sadar', 'Other / Not listed'],
        'Rangpur' => ['Badarganj', 'Gangachara', 'Haragach', 'Kaunia', 'Kotwali', 'Mahiganj', 'Mithapukur', 'Parshuram', 'Pirgacha', 'Pirganj', 'Rangpur Sadar', 'Tajhat', 'Taraganj', 'Tukuria', 'Other / Not listed'],
        'Satkhira' => ['Assasuni', 'Debhata', 'Kalaroa', 'Kaliaganj', 'Satkhira Sadar', 'Shyamnagar', 'Tala', 'Other / Not listed'],
        'Shariatpur' => ['Bhedarganj', 'Damudya', 'Gosairhat', 'Naria', 'Shariatpur Sadar', 'Zajira', 'Other / Not listed'],
        'Sherpur' => ['Jhenaigati', 'Nakla', 'Nalitabari', 'Sherpur Sadar', 'Sreebardi', 'Other / Not listed'],
        'Sirajganj' => ['Belkuchi', 'Chauhali', 'Kamarbandha', 'Kazipur', 'Raiganj', 'Shahjadpur', 'Sirajganj Sadar', 'Tarash', 'Ullapara', 'Other / Not listed'],
        'Sunamganj' => ['Bishwambharpur', 'Chatak', 'Dewarabazar', 'Dharmapasha', 'Dirai', 'Jagannathpur', 'Jamalganj', 'Madhyanagar', 'Shalla', 'South Sunamganj', 'Sunamganj Sadar', 'Taherpur', 'Other / Not listed'],
        'Sylhet' => ['Airport', 'Balaganj', 'Beanibazar', 'Bishwanath', 'Dakshin Surma', 'Fenchuganj', 'Golapganj', 'Gowainghat', 'Jaintapur', 'Jalalabad', 'Kanaighat', 'Komponganj', 'Kotwali', 'Moglabazar', 'Osmani', 'Shah Paran', 'South Surma', 'Sylhet Sadar', 'Zakiganj', 'Other / Not listed'],
        'Tangail' => ['Basail', 'Bhuapur', 'Delduar', 'Dhanbari', 'Ghatail', 'Gopalpur', 'Kalihati', 'Madhupur', 'Mirzapur', 'Nagarpur', 'Sakhipur', 'Tangail Sadar', 'Other / Not listed'],
        'Thakurgaon' => ['Baliadangi', 'Haripur', 'Pirganj', 'Ranisankail', 'Thakurgaon Sadar', 'Other / Not listed'],
    ];

    public static function districts(): array
    {
        return array_keys(self::DISTRICT_AREAS);
    }

    public static function districtAreas(): array
    {
        return self::DISTRICT_AREAS;
    }

    public static function areasFor(?string $district): array
    {
        return self::DISTRICT_AREAS[trim((string) $district)] ?? [];
    }

    public static function dhakaSuburbanAreas(): array
    {
        return self::DHAKA_SUBURBAN_AREAS;
    }

    public static function isDhakaSuburban(?string $district, ?string $area): bool
    {
        if (strcasecmp(trim((string) $district), 'Dhaka') !== 0) {
            return false;
        }

        $area = trim((string) $area);

        foreach (self::DHAKA_SUBURBAN_AREAS as $suburbanArea) {
            if (strcasecmp($area, $suburbanArea) === 0) {
                return true;
            }
        }

        return false;
    }

    public static function zoneForLocation(?string $district, ?string $area = null): string
    {
        if (strcasecmp(trim((string) $district), 'Dhaka') !== 0) {
            return 'outside_dhaka';
        }

        return self::isDhakaSuburban($district, $area)
            ? 'dhaka_suburban'
            : 'inside_dhaka';
    }

    public static function zoneForDistrict(?string $district): string
    {
        return self::zoneForLocation($district);
    }
}
