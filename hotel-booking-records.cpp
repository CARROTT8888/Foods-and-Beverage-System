// IF YOU SEE IT, JUST IGNORE THIS FILE AND THE CODE
// WE'RE DOING ANOTHER ASSIGNMENT AND PROJECT IN THIS FILE

#include <iostream>
#include <iomanip>
#include <chrono>
using namespace std;

struct Booking
{
    int bookingId;
    string customerName;
    double roomPrice;
    int checkInDay;
    /*string checkedInDate;
    string checkedOutDate;*/
};

int comparisons = 0;
int swapsCount = 0;

void displayBookings(Booking booking[], int size)
{
    cout << "Booking ID" << " " << "Customer Name" << " " << "Room Price" << " " << "check In" << endl;
    cout << "--------------------------------------------------" << endl;

    for (int i = 0; i < size; i++)
    {
        cout << booking[i].bookingId << " ";
        cout << booking[i].customerName << " ";
        cout << booking[i].roomPrice << " ";
        cout << booking[i].checkInDay << endl;
    }
}

void swapBooking(Booking &a, Booking &b)
{
    Booking temp = a;
    a = b;
    b = temp;

    swapsCount++;
}

// to heapify a subtree rooted with node i which is a string in Booking array. n is size of heap
void heapifyFunction(Booking booking[], int n, int i)
{
    int largest = i;
    int left = 2 * i + 1;
    int right = 2 * 1 + 2;

    // If left child is larger than root
    if (left < n && booking[left].bookingId > booking[largest].bookingId)
    {
        comparisons++;
        largest = left;
    }

    // If right child is larger than largest so far
    if (right < n && booking[right].bookingId > booking[largest].bookingId)
    {
        comparisons++;
        largest = right;
    }

    // If the largest is not a root
    if (largest != i)
    {
        /*swap(booking[i], booking[largest]);*/
        swapBooking(booking[i], booking[largest]);

        // Recursively heapify the affected sub-tree
        heapifyFunction(booking, n, largest);
    }
}

int main()
{
    /*cout << "Please enter student's ID and marks" << endl;

    student *ptr = new student[5];

    for (int i=0; i < 5; i++)
    {
        cout << "\tStudent " << (i+1) << " ID : ";
        cin >> (*ptr).studentId;
        cout << "\tStudent " << (i+1) << " mark : ";
        cin >> (*ptr).mark;
    }*/

    const int SIZE = 100;

    Booking booking[SIZE] =
        {
            {1001, "Customer1", 120},
            {1002, "Customer2", 135},
            {1003, "Customer3", 150},
            {1004, "Customer4", 165},
            {1005, "Customer5", 180},
            {1006, "Customer6", 195},
            {1007, "Customer7", 210},
            {1008, "Customer8", 225},
            {1009, "Customer9", 240},
            {1010, "Customer10", 255},

            {1011, "Customer11", 270},
            {1012, "Customer12", 285},
            {1013, "Customer13", 300},
            {1014, "Customer14", 315},
            {1015, "Customer15", 330},
            {1016, "Customer16", 345},
            {1017, "Customer17", 360},
            {1018, "Customer18", 375},
            {1019, "Customer19", 390},
            {1020, "Customer20", 405},

            {1021, "Customer21", 420},
            {1022, "Customer22", 435},
            {1023, "Customer23", 450},
            {1024, "Customer24", 465},
            {1025, "Customer25", 480},
            {1026, "Customer26", 495},
            {1027, "Customer27", 510},
            {1028, "Customer28", 525},
            {1029, "Customer29", 540},
            {1030, "Customer30", 555},

            {1031, "Customer31", 570},
            {1032, "Customer32", 585},
            {1033, "Customer33", 600},
            {1034, "Customer34", 615},
            {1035, "Customer35", 630},
            {1036, "Customer36", 645},
            {1037, "Customer37", 660},
            {1038, "Customer38", 675},
            {1039, "Customer39", 690},
            {1040, "Customer40", 705},

            {1041, "Customer41", 720},
            {1042, "Customer42", 735},
            {1043, "Customer43", 750},
            {1044, "Customer44", 765},
            {1045, "Customer45", 780},
            {1046, "Customer46", 795},
            {1047, "Customer47", 810},
            {1048, "Customer48", 825},
            {1049, "Customer49", 840},
            {1050, "Customer50", 855},

            {1051, "Customer51", 870},
            {1052, "Customer52", 885},
            {1053, "Customer53", 900},
            {1054, "Customer54", 915},
            {1055, "Customer55", 930},
            {1056, "Customer56", 945},
            {1057, "Customer57", 960},
            {1058, "Customer58", 975},
            {1059, "Customer59", 990},
            {1060, "Customer60", 1005},

            {1061, "Customer61", 1020},
            {1062, "Customer62", 1035},
            {1063, "Customer63", 1050},
            {1064, "Customer64", 1065},
            {1065, "Customer65", 1080},
            {1066, "Customer66", 1095},
            {1067, "Customer67", 1110},
            {1068, "Customer68", 1125},
            {1069, "Customer69", 1140},
            {1070, "Customer70", 1155},

            {1071, "Customer71", 1170},
            {1072, "Customer72", 1185},
            {1073, "Customer73", 1200},
            {1074, "Customer74", 1215},
            {1075, "Customer75", 1230},
            {1076, "Customer76", 1245},
            {1077, "Customer77", 1260},
            {1078, "Customer78", 1275},
            {1079, "Customer79", 1290},
            {1080, "Customer80", 1305},

            {1081, "Customer81", 1320},
            {1082, "Customer82", 1335},
            {1083, "Customer83", 1350},
            {1084, "Customer84", 1365},
            {1085, "Customer85", 1380},
            {1086, "Customer86", 1395},
            {1087, "Customer87", 1410},
            {1088, "Customer88", 1425},
            {1089, "Customer89", 1440},
            {1090, "Customer90", 1455},

            {1091, "Customer91", 1470},
            {1092, "Customer92", 1485},
            {1093, "Customer93", 1500},
            {1094, "Customer94", 1515},
            {1095, "Customer95", 1530},
            {1096, "Customer96", 1545},
            {1097, "Customer97", 1560},
            {1098, "Customer98", 1575},
            {1099, "Customer99", 1590},
            {1100, "Customer100", 1605}};

    return 0;
}