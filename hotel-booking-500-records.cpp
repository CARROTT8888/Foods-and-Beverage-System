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
    cout << "Booking ID\t" << "Customer Name\t" << "Room Price\t" << "check In Day\t" << endl;
    cout << "--------------------------------------------------" << endl;

    for (int i = 0; i < size; i++)
    {
        cout << booking[i].bookingId << "\t";
        cout << "\t" << booking[i].customerName;
        cout << "\t" << booking[i].roomPrice << "\t";
        cout << "\t" << booking[i].checkInDay << endl;
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
void heapify(Booking booking[], int n, int i)
{
    int largest = i;
    int left = 2 * i + 1;
    int right = 2 * i + 2;

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
        heapify(booking, n, largest);
    }
}

// main function to do heap sort
void heapSort(Booking booking[], int n)
{
    // Build heap (rearrange array)
    for (int i = n / 2 - 1; i >= 0; i--)
    {
        heapify(booking, n, i);
    }

    // One by one extract an element from heap
    for (int i = n - 1; i >= 0; i--)
    {
        // Move current root to end
        swapBooking(booking[0], booking[i]);

        // call max heapify on the reduced heap
        heapify(booking, i, 0);
    }
}

// main function to do shell sort
void shellSort(Booking booking[], int n)
{
    // Start with a large gap, then reduce it step by step
    for (int gap = n / 2; gap > 0; gap /= 2)
    {
        // Perform a "gapped" insertion sort for this gap size
        for (int i = gap; i < n; i++)
        {

            // Current element to be placed correctly
            Booking temp = booking[i];
            int j;

            for (j = i; j >= gap; j -= gap)
            {
                comparisons++;

                if (booking[j - gap].bookingId > temp.bookingId)
                {
                    booking[j] = booking[j - gap];
                    swapsCount++;
                }
                else
                {
                    break;
                }
            }

            // Place temp in its correct location
            booking[j] = temp;
            swapsCount++;
        }
    }
}

void resetCounter()
{
    comparisons = 0;
    swapsCount = 0;
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

    const int SIZE = 500;

    // 100 hotel booking records
    Booking booking[SIZE] =
        {
            {1001, "Customer1", 120, 1},
            {1002, "Customer2", 135, 3},
            {1003, "Customer3", 150, 5},
            {1004, "Customer4", 165, 7},
            {1005, "Customer5", 180, 10},
            {1006, "Customer6", 195, 12},
            {1007, "Customer7", 210, 14},
            {1008, "Customer8", 225, 15},
            {1009, "Customer9", 240, 18},
            {1010, "Customer10", 255, 20},

            {1011, "Customer11", 270, 22},
            {1012, "Customer12", 285, 25},
            {1013, "Customer13", 300, 28},
            {1014, "Customer14", 315, 30},
            {1015, "Customer15", 330, 1},
            {1016, "Customer16", 345, 2},
            {1017, "Customer17", 360, 4},
            {1018, "Customer18", 375, 6},
            {1019, "Customer19", 390, 8},
            {1020, "Customer20", 405, 11},

            {1021, "Customer21", 420, 13},
            {1022, "Customer22", 435, 15},
            {1023, "Customer23", 450, 17},
            {1024, "Customer24", 465, 19},
            {1025, "Customer25", 480, 21},
            {1026, "Customer26", 495, 24},
            {1027, "Customer27", 510, 26},
            {1028, "Customer28", 525, 27},
            {1029, "Customer29", 540, 29},
            {1030, "Customer30", 555, 31},

            {1031, "Customer31", 570, 2},
            {1032, "Customer32", 585, 3},
            {1033, "Customer33", 600, 5},
            {1034, "Customer34", 615, 6},
            {1035, "Customer35", 630, 9},
            {1036, "Customer36", 645, 12},
            {1037, "Customer37", 660, 14},
            {1038, "Customer38", 675, 16},
            {1039, "Customer39", 690, 18},
            {1040, "Customer40", 705, 20},

            {1041, "Customer41", 720, 23},
            {1042, "Customer42", 735, 25},
            {1043, "Customer43", 750, 27},
            {1044, "Customer44", 765, 28},
            {1045, "Customer45", 780, 30},
            {1046, "Customer46", 795, 1},
            {1047, "Customer47", 810, 3},
            {1048, "Customer48", 825, 4},
            {1049, "Customer49", 840, 7},
            {1050, "Customer50", 855, 9},

            {1051, "Customer51", 870, 11},
            {1052, "Customer52", 885, 13},
            {1053, "Customer53", 900, 15},
            {1054, "Customer54", 915, 17},
            {1055, "Customer55", 930, 19},
            {1056, "Customer56", 945, 22},
            {1057, "Customer57", 960, 24},
            {1058, "Customer58", 975, 26},
            {1059, "Customer59", 990, 28},
            {1060, "Customer60", 1005, 31},

            {1061, "Customer61", 1020, 2},
            {1062, "Customer62", 1035, 4},
            {1063, "Customer63", 1050, 6},
            {1064, "Customer64", 1065, 8},
            {1065, "Customer65", 1080, 10},
            {1066, "Customer66", 1095, 12},
            {1067, "Customer67", 1110, 15},
            {1068, "Customer68", 1125, 17},
            {1069, "Customer69", 1140, 19},
            {1070, "Customer70", 1155, 21},

            {1071, "Customer71", 1170, 23},
            {1072, "Customer72", 1185, 25},
            {1073, "Customer73", 1200, 26},
            {1074, "Customer74", 1215, 29},
            {1075, "Customer75", 1230, 30},
            {1076, "Customer76", 1245, 1},
            {1077, "Customer77", 1260, 3},
            {1078, "Customer78", 1275, 5},
            {1079, "Customer79", 1290, 7},
            {1080, "Customer80", 1305, 9},

            {1081, "Customer81", 1320, 11},
            {1082, "Customer82", 1335, 14},
            {1083, "Customer83", 1350, 16},
            {1084, "Customer84", 1365, 18},
            {1085, "Customer85", 1380, 20},
            {1086, "Customer86", 1395, 22},
            {1087, "Customer87", 1410, 24},
            {1088, "Customer88", 1425, 27},
            {1089, "Customer89", 1440, 29},
            {1090, "Customer90", 1455, 30},

            {1091, "Customer91", 1470, 1},
            {1092, "Customer92", 1485, 3},
            {1093, "Customer93", 1500, 5},
            {1094, "Customer94", 1515, 8},
            {1095, "Customer95", 1530, 10},
            {1096, "Customer96", 1545, 12},
            {1097, "Customer97", 1560, 14},
            {1098, "Customer98", 1575, 17},
            {1099, "Customer99", 1590, 19},
            {1100, "Customer100", 1605, 22},

            {1001, "Customer1", 120, 1},
            {1002, "Customer2", 135, 3},
            {1003, "Customer3", 150, 5},
            {1004, "Customer4", 165, 7},
            {1005, "Customer5", 180, 10},
            {1006, "Customer6", 195, 12},
            {1007, "Customer7", 210, 14},
            {1008, "Customer8", 225, 15},
            {1009, "Customer9", 240, 18},
            {1010, "Customer10", 255, 20},

            {1011, "Customer11", 270, 22},
            {1012, "Customer12", 285, 25},
            {1013, "Customer13", 300, 28},
            {1014, "Customer14", 315, 30},
            {1015, "Customer15", 330, 1},
            {1016, "Customer16", 345, 2},
            {1017, "Customer17", 360, 4},
            {1018, "Customer18", 375, 6},
            {1019, "Customer19", 390, 8},
            {1020, "Customer20", 405, 11},

            {1021, "Customer21", 420, 13},
            {1022, "Customer22", 435, 15},
            {1023, "Customer23", 450, 17},
            {1024, "Customer24", 465, 19},
            {1025, "Customer25", 480, 21},
            {1026, "Customer26", 495, 24},
            {1027, "Customer27", 510, 26},
            {1028, "Customer28", 525, 27},
            {1029, "Customer29", 540, 29},
            {1030, "Customer30", 555, 31},

            {1031, "Customer31", 570, 2},
            {1032, "Customer32", 585, 3},
            {1033, "Customer33", 600, 5},
            {1034, "Customer34", 615, 6},
            {1035, "Customer35", 630, 9},
            {1036, "Customer36", 645, 12},
            {1037, "Customer37", 660, 14},
            {1038, "Customer38", 675, 16},
            {1039, "Customer39", 690, 18},
            {1040, "Customer40", 705, 20},

            {1041, "Customer41", 720, 23},
            {1042, "Customer42", 735, 25},
            {1043, "Customer43", 750, 27},
            {1044, "Customer44", 765, 28},
            {1045, "Customer45", 780, 30},
            {1046, "Customer46", 795, 1},
            {1047, "Customer47", 810, 3},
            {1048, "Customer48", 825, 4},
            {1049, "Customer49", 840, 7},
            {1050, "Customer50", 855, 9},

            {1051, "Customer51", 870, 11},
            {1052, "Customer52", 885, 13},
            {1053, "Customer53", 900, 15},
            {1054, "Customer54", 915, 17},
            {1055, "Customer55", 930, 19},
            {1056, "Customer56", 945, 22},
            {1057, "Customer57", 960, 24},
            {1058, "Customer58", 975, 26},
            {1059, "Customer59", 990, 28},
            {1060, "Customer60", 1005, 31},

            {1061, "Customer61", 1020, 2},
            {1062, "Customer62", 1035, 4},
            {1063, "Customer63", 1050, 6},
            {1064, "Customer64", 1065, 8},
            {1065, "Customer65", 1080, 10},
            {1066, "Customer66", 1095, 12},
            {1067, "Customer67", 1110, 15},
            {1068, "Customer68", 1125, 17},
            {1069, "Customer69", 1140, 19},
            {1070, "Customer70", 1155, 21},

            {1071, "Customer71", 1170, 23},
            {1072, "Customer72", 1185, 25},
            {1073, "Customer73", 1200, 26},
            {1074, "Customer74", 1215, 29},
            {1075, "Customer75", 1230, 30},
            {1076, "Customer76", 1245, 1},
            {1077, "Customer77", 1260, 3},
            {1078, "Customer78", 1275, 5},
            {1079, "Customer79", 1290, 7},
            {1080, "Customer80", 1305, 9},

            {1081, "Customer81", 1320, 11},
            {1082, "Customer82", 1335, 14},
            {1083, "Customer83", 1350, 16},
            {1084, "Customer84", 1365, 18},
            {1085, "Customer85", 1380, 20},
            {1086, "Customer86", 1395, 22},
            {1087, "Customer87", 1410, 24},
            {1088, "Customer88", 1425, 27},
            {1089, "Customer89", 1440, 29},
            {1090, "Customer90", 1455, 30},

            {1091, "Customer91", 1470, 1},
            {1092, "Customer92", 1485, 3},
            {1093, "Customer93", 1500, 5},
            {1094, "Customer94", 1515, 8},
            {1095, "Customer95", 1530, 10},
            {1096, "Customer96", 1545, 12},
            {1097, "Customer97", 1560, 14},
            {1098, "Customer98", 1575, 17},
            {1099, "Customer99", 1590, 19},
            {1100, "Customer100", 1605, 22},

            {1001, "Customer1", 120, 1},
            {1002, "Customer2", 135, 3},
            {1003, "Customer3", 150, 5},
            {1004, "Customer4", 165, 7},
            {1005, "Customer5", 180, 10},
            {1006, "Customer6", 195, 12},
            {1007, "Customer7", 210, 14},
            {1008, "Customer8", 225, 15},
            {1009, "Customer9", 240, 18},
            {1010, "Customer10", 255, 20},

            {1011, "Customer11", 270, 22},
            {1012, "Customer12", 285, 25},
            {1013, "Customer13", 300, 28},
            {1014, "Customer14", 315, 30},
            {1015, "Customer15", 330, 1},
            {1016, "Customer16", 345, 2},
            {1017, "Customer17", 360, 4},
            {1018, "Customer18", 375, 6},
            {1019, "Customer19", 390, 8},
            {1020, "Customer20", 405, 11},

            {1021, "Customer21", 420, 13},
            {1022, "Customer22", 435, 15},
            {1023, "Customer23", 450, 17},
            {1024, "Customer24", 465, 19},
            {1025, "Customer25", 480, 21},
            {1026, "Customer26", 495, 24},
            {1027, "Customer27", 510, 26},
            {1028, "Customer28", 525, 27},
            {1029, "Customer29", 540, 29},
            {1030, "Customer30", 555, 31},

            {1031, "Customer31", 570, 2},
            {1032, "Customer32", 585, 3},
            {1033, "Customer33", 600, 5},
            {1034, "Customer34", 615, 6},
            {1035, "Customer35", 630, 9},
            {1036, "Customer36", 645, 12},
            {1037, "Customer37", 660, 14},
            {1038, "Customer38", 675, 16},
            {1039, "Customer39", 690, 18},
            {1040, "Customer40", 705, 20},

            {1041, "Customer41", 720, 23},
            {1042, "Customer42", 735, 25},
            {1043, "Customer43", 750, 27},
            {1044, "Customer44", 765, 28},
            {1045, "Customer45", 780, 30},
            {1046, "Customer46", 795, 1},
            {1047, "Customer47", 810, 3},
            {1048, "Customer48", 825, 4},
            {1049, "Customer49", 840, 7},
            {1050, "Customer50", 855, 9},

            {1051, "Customer51", 870, 11},
            {1052, "Customer52", 885, 13},
            {1053, "Customer53", 900, 15},
            {1054, "Customer54", 915, 17},
            {1055, "Customer55", 930, 19},
            {1056, "Customer56", 945, 22},
            {1057, "Customer57", 960, 24},
            {1058, "Customer58", 975, 26},
            {1059, "Customer59", 990, 28},
            {1060, "Customer60", 1005, 31},

            {1061, "Customer61", 1020, 2},
            {1062, "Customer62", 1035, 4},
            {1063, "Customer63", 1050, 6},
            {1064, "Customer64", 1065, 8},
            {1065, "Customer65", 1080, 10},
            {1066, "Customer66", 1095, 12},
            {1067, "Customer67", 1110, 15},
            {1068, "Customer68", 1125, 17},
            {1069, "Customer69", 1140, 19},
            {1070, "Customer70", 1155, 21},

            {1071, "Customer71", 1170, 23},
            {1072, "Customer72", 1185, 25},
            {1073, "Customer73", 1200, 26},
            {1074, "Customer74", 1215, 29},
            {1075, "Customer75", 1230, 30},
            {1076, "Customer76", 1245, 1},
            {1077, "Customer77", 1260, 3},
            {1078, "Customer78", 1275, 5},
            {1079, "Customer79", 1290, 7},
            {1080, "Customer80", 1305, 9},

            {1081, "Customer81", 1320, 11},
            {1082, "Customer82", 1335, 14},
            {1083, "Customer83", 1350, 16},
            {1084, "Customer84", 1365, 18},
            {1085, "Customer85", 1380, 20},
            {1086, "Customer86", 1395, 22},
            {1087, "Customer87", 1410, 24},
            {1088, "Customer88", 1425, 27},
            {1089, "Customer89", 1440, 29},
            {1090, "Customer90", 1455, 30},

            {1091, "Customer91", 1470, 1},
            {1092, "Customer92", 1485, 3},
            {1093, "Customer93", 1500, 5},
            {1094, "Customer94", 1515, 8},
            {1095, "Customer95", 1530, 10},
            {1096, "Customer96", 1545, 12},
            {1097, "Customer97", 1560, 14},
            {1098, "Customer98", 1575, 17},
            {1099, "Customer99", 1590, 19},
            {1100, "Customer100", 1605, 22},

            {1001, "Customer1", 120, 1},
            {1002, "Customer2", 135, 3},
            {1003, "Customer3", 150, 5},
            {1004, "Customer4", 165, 7},
            {1005, "Customer5", 180, 10},
            {1006, "Customer6", 195, 12},
            {1007, "Customer7", 210, 14},
            {1008, "Customer8", 225, 15},
            {1009, "Customer9", 240, 18},
            {1010, "Customer10", 255, 20},

            {1011, "Customer11", 270, 22},
            {1012, "Customer12", 285, 25},
            {1013, "Customer13", 300, 28},
            {1014, "Customer14", 315, 30},
            {1015, "Customer15", 330, 1},
            {1016, "Customer16", 345, 2},
            {1017, "Customer17", 360, 4},
            {1018, "Customer18", 375, 6},
            {1019, "Customer19", 390, 8},
            {1020, "Customer20", 405, 11},

            {1021, "Customer21", 420, 13},
            {1022, "Customer22", 435, 15},
            {1023, "Customer23", 450, 17},
            {1024, "Customer24", 465, 19},
            {1025, "Customer25", 480, 21},
            {1026, "Customer26", 495, 24},
            {1027, "Customer27", 510, 26},
            {1028, "Customer28", 525, 27},
            {1029, "Customer29", 540, 29},
            {1030, "Customer30", 555, 31},

            {1031, "Customer31", 570, 2},
            {1032, "Customer32", 585, 3},
            {1033, "Customer33", 600, 5},
            {1034, "Customer34", 615, 6},
            {1035, "Customer35", 630, 9},
            {1036, "Customer36", 645, 12},
            {1037, "Customer37", 660, 14},
            {1038, "Customer38", 675, 16},
            {1039, "Customer39", 690, 18},
            {1040, "Customer40", 705, 20},

            {1041, "Customer41", 720, 23},
            {1042, "Customer42", 735, 25},
            {1043, "Customer43", 750, 27},
            {1044, "Customer44", 765, 28},
            {1045, "Customer45", 780, 30},
            {1046, "Customer46", 795, 1},
            {1047, "Customer47", 810, 3},
            {1048, "Customer48", 825, 4},
            {1049, "Customer49", 840, 7},
            {1050, "Customer50", 855, 9},

            {1051, "Customer51", 870, 11},
            {1052, "Customer52", 885, 13},
            {1053, "Customer53", 900, 15},
            {1054, "Customer54", 915, 17},
            {1055, "Customer55", 930, 19},
            {1056, "Customer56", 945, 22},
            {1057, "Customer57", 960, 24},
            {1058, "Customer58", 975, 26},
            {1059, "Customer59", 990, 28},
            {1060, "Customer60", 1005, 31},

            {1061, "Customer61", 1020, 2},
            {1062, "Customer62", 1035, 4},
            {1063, "Customer63", 1050, 6},
            {1064, "Customer64", 1065, 8},
            {1065, "Customer65", 1080, 10},
            {1066, "Customer66", 1095, 12},
            {1067, "Customer67", 1110, 15},
            {1068, "Customer68", 1125, 17},
            {1069, "Customer69", 1140, 19},
            {1070, "Customer70", 1155, 21},

            {1071, "Customer71", 1170, 23},
            {1072, "Customer72", 1185, 25},
            {1073, "Customer73", 1200, 26},
            {1074, "Customer74", 1215, 29},
            {1075, "Customer75", 1230, 30},
            {1076, "Customer76", 1245, 1},
            {1077, "Customer77", 1260, 3},
            {1078, "Customer78", 1275, 5},
            {1079, "Customer79", 1290, 7},
            {1080, "Customer80", 1305, 9},

            {1081, "Customer81", 1320, 11},
            {1082, "Customer82", 1335, 14},
            {1083, "Customer83", 1350, 16},
            {1084, "Customer84", 1365, 18},
            {1085, "Customer85", 1380, 20},
            {1086, "Customer86", 1395, 22},
            {1087, "Customer87", 1410, 24},
            {1088, "Customer88", 1425, 27},
            {1089, "Customer89", 1440, 29},
            {1090, "Customer90", 1455, 30},

            {1091, "Customer91", 1470, 1},
            {1092, "Customer92", 1485, 3},
            {1093, "Customer93", 1500, 5},
            {1094, "Customer94", 1515, 8},
            {1095, "Customer95", 1530, 10},
            {1096, "Customer96", 1545, 12},
            {1097, "Customer97", 1560, 14},
            {1098, "Customer98", 1575, 17},
            {1099, "Customer99", 1590, 19},
            {1100, "Customer100", 1605, 22},

            {1001, "Customer1", 120, 1},
            {1002, "Customer2", 135, 3},
            {1003, "Customer3", 150, 5},
            {1004, "Customer4", 165, 7},
            {1005, "Customer5", 180, 10},
            {1006, "Customer6", 195, 12},
            {1007, "Customer7", 210, 14},
            {1008, "Customer8", 225, 15},
            {1009, "Customer9", 240, 18},
            {1010, "Customer10", 255, 20},

            {1011, "Customer11", 270, 22},
            {1012, "Customer12", 285, 25},
            {1013, "Customer13", 300, 28},
            {1014, "Customer14", 315, 30},
            {1015, "Customer15", 330, 1},
            {1016, "Customer16", 345, 2},
            {1017, "Customer17", 360, 4},
            {1018, "Customer18", 375, 6},
            {1019, "Customer19", 390, 8},
            {1020, "Customer20", 405, 11},

            {1021, "Customer21", 420, 13},
            {1022, "Customer22", 435, 15},
            {1023, "Customer23", 450, 17},
            {1024, "Customer24", 465, 19},
            {1025, "Customer25", 480, 21},
            {1026, "Customer26", 495, 24},
            {1027, "Customer27", 510, 26},
            {1028, "Customer28", 525, 27},
            {1029, "Customer29", 540, 29},
            {1030, "Customer30", 555, 31},

            {1031, "Customer31", 570, 2},
            {1032, "Customer32", 585, 3},
            {1033, "Customer33", 600, 5},
            {1034, "Customer34", 615, 6},
            {1035, "Customer35", 630, 9},
            {1036, "Customer36", 645, 12},
            {1037, "Customer37", 660, 14},
            {1038, "Customer38", 675, 16},
            {1039, "Customer39", 690, 18},
            {1040, "Customer40", 705, 20},

            {1041, "Customer41", 720, 23},
            {1042, "Customer42", 735, 25},
            {1043, "Customer43", 750, 27},
            {1044, "Customer44", 765, 28},
            {1045, "Customer45", 780, 30},
            {1046, "Customer46", 795, 1},
            {1047, "Customer47", 810, 3},
            {1048, "Customer48", 825, 4},
            {1049, "Customer49", 840, 7},
            {1050, "Customer50", 855, 9},

            {1051, "Customer51", 870, 11},
            {1052, "Customer52", 885, 13},
            {1053, "Customer53", 900, 15},
            {1054, "Customer54", 915, 17},
            {1055, "Customer55", 930, 19},
            {1056, "Customer56", 945, 22},
            {1057, "Customer57", 960, 24},
            {1058, "Customer58", 975, 26},
            {1059, "Customer59", 990, 28},
            {1060, "Customer60", 1005, 31},

            {1061, "Customer61", 1020, 2},
            {1062, "Customer62", 1035, 4},
            {1063, "Customer63", 1050, 6},
            {1064, "Customer64", 1065, 8},
            {1065, "Customer65", 1080, 10},
            {1066, "Customer66", 1095, 12},
            {1067, "Customer67", 1110, 15},
            {1068, "Customer68", 1125, 17},
            {1069, "Customer69", 1140, 19},
            {1070, "Customer70", 1155, 21},

            {1071, "Customer71", 1170, 23},
            {1072, "Customer72", 1185, 25},
            {1073, "Customer73", 1200, 26},
            {1074, "Customer74", 1215, 29},
            {1075, "Customer75", 1230, 30},
            {1076, "Customer76", 1245, 1},
            {1077, "Customer77", 1260, 3},
            {1078, "Customer78", 1275, 5},
            {1079, "Customer79", 1290, 7},
            {1080, "Customer80", 1305, 9},

            {1081, "Customer81", 1320, 11},
            {1082, "Customer82", 1335, 14},
            {1083, "Customer83", 1350, 16},
            {1084, "Customer84", 1365, 18},
            {1085, "Customer85", 1380, 20},
            {1086, "Customer86", 1395, 22},
            {1087, "Customer87", 1410, 24},
            {1088, "Customer88", 1425, 27},
            {1089, "Customer89", 1440, 29},
            {1090, "Customer90", 1455, 30},

            {1091, "Customer91", 1470, 1},
            {1092, "Customer92", 1485, 3},
            {1093, "Customer93", 1500, 5},
            {1094, "Customer94", 1515, 8},
            {1095, "Customer95", 1530, 10},
            {1096, "Customer96", 1545, 12},
            {1097, "Customer97", 1560, 14},
            {1098, "Customer98", 1575, 17},
            {1099, "Customer99", 1590, 19},
            {1100, "Customer100", 1605, 22},
        };

    int choice;

    cout << "Hotel Booking Records\n";
    cout << "1. Display Records." << endl;
    cout << "2. Heap Sort" << endl;
    cout << "3. Shell Sort" << endl;
    cout << "Enter choice: ";
    cin >> choice;

    // displays hotel booking records
    if (choice == 1)
    {
        displayBookings(booking, SIZE);
    }

    else if (choice == 2)
    {
        resetCounter();
        // using high resolution clock to get current time
        auto start = chrono::high_resolution_clock::now();
        heapSort(booking, SIZE);
        auto end = chrono::high_resolution_clock::now();
        chrono::duration<double, milli> duration = end - start;

        cout << "Heap Sort Result" << endl;
        displayBookings(booking, SIZE);

        cout << "Execution Time: " << duration.count() << " ms" << endl;
        cout << "Comparisons: " << comparisons << endl;
        cout << "swaps: " << swapsCount;
    }

    else if (choice == 3)
    {
        resetCounter();
        // using high resolution clock to get current time
        auto start = chrono::high_resolution_clock::now();
        shellSort(booking, SIZE);
        auto end = chrono::high_resolution_clock::now();
        chrono::duration<double, milli> duration = end - start;

        cout << "Shell Sort Result" << endl;
        displayBookings(booking, SIZE);

        cout << "Execution Time: " << duration.count() << " ms" << endl;
        cout << "Comparisons: " << comparisons << endl;
        cout << "swaps: " << swapsCount;
    }

    else
    {
        cout << "Invalid choice!";
    }

    return 0;
}