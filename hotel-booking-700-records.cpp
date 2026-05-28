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

void displayArray(Booking booking[], int size)
{
    cout << "[";
    for (int i = 0; i < size; i++)
    {
        cout << booking[i].bookingId << (i == size - 1 ? "" : ", ");
    }
    cout << "]" << endl;
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

void heapSortDemo(Booking booking[], int n)
{
    cout << "Heap Array: ";
    displayArray(booking, n);

    // Build heap
    for (int i = n / 2 - 1; i >= 0; i--)
    {
        heapify(booking, n, i);
    }

    cout << "After Building Max Heap: ";
    displayArray(booking, n);
    cout << "\n"
         << endl;

    int step = 1;
    for (int i = n - 1; i >= 0; i--)
    {
        cout << "Step " << (step++) << ": Swap Root(" << booking[0].bookingId << ") with last(" << booking[i].bookingId << ")" << endl;
        swapBooking(booking[0], booking[i]);
        heapify(booking, i, 0);
        cout << "Heap Array State: ";
        displayArray(booking, n);
        cout << endl;
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

void shellSortDemo(Booking booking[], int n)
{
    cout << "Shell Array: ";
    displayArray(booking, n);
    cout << "\n"
         << endl;

    int step = 1;
    for (int gap = n / 2; gap > 0; gap /= 2)
    {
        cout << "Current Gap Size = " << gap << endl;
        for (int i = gap; i < n; i++)
        {
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
            booking[j] = temp;
            swapsCount++;

            cout << "Step " << (step++) << " (Insert ID " << temp.bookingId << "): ";
            displayArray(booking, n);
        }
        cout << endl;
    }
}

void createBestCase(Booking source[], Booking target[], int size)
{
    for (int i = 0; i < size; i++)
    {
        target[i] = source[i];
    }

    shellSort(target, size);
}

void createWorstCase(Booking bestCase[], Booking target[], int size)
{
    for (int i = 0; i < size; i++)
    {
        target[i] = bestCase[size - 1 - i];
    }
}

void resetCounter()
{
    comparisons = 0;
    swapsCount = 0;
}

void algorithm(Booking booking[], int size, int algorithmChoice)
{
    resetCounter();
    auto start = chrono::high_resolution_clock::now();

    if (algorithmChoice == 1)
    {
        heapSort(booking, size);
    }

    else
    {
        shellSort(booking, size);
    }

    auto end = chrono::high_resolution_clock::now();

    chrono::duration<double, milli> duration = end - start;

    cout << "Execution Time: " << duration.count() << " ms" << endl;
    cout << "Comparisons: " << comparisons << endl;
    cout << "Swaps: " << swapsCount << endl;
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

    const int SIZE = 700;

    // 700 hotel booking records
    Booking originalBooking[SIZE] =
        {
            {1234, "Customer412", 890, 14},
            {1556, "Customer128", 1420, 3},
            {1102, "Customer389", 310, 22},
            {1489, "Customer572", 1150, 29},
            {1315, "Customer201", 620, 7},
            {1178, "Customer445", 450, 1},
            {1592, "Customer103", 1580, 18},
            {1367, "Customer334", 780, 25},
            {1211, "Customer519", 510, 12},
            {1445, "Customer267", 1310, 30},

            {1512, "Customer155", 1210, 5},
            {1134, "Customer498", 380, 19},
            {1399, "Customer212", 940, 26},
            {1278, "Customer367", 710, 8},
            {1577, "Customer588", 1490, 11},
            {1190, "Customer114", 490, 31},
            {1423, "Customer431", 1050, 15},
            {1333, "Customer299", 680, 2},
            {1471, "Customer504", 1280, 24},
            {1256, "Customer187", 830, 17},

            {1115, "Customer322", 340, 9},
            {1533, "Customer541", 1360, 28},
            {1351, "Customer149", 740, 13},
            {1202, "Customer476", 530, 6},
            {1588, "Customer233", 1540, 21},
            {1411, "Customer395", 990, 4},
            {1156, "Customer511", 410, 23},
            {1459, "Customer177", 1120, 16},
            {1290, "Customer308", 790, 10},
            {1378, "Customer462", 870, 27},

            {1544, "Customer254", 1390, 2},
            {1122, "Customer533", 320, 20},
            {1495, "Customer121", 1180, 14},
            {1245, "Customer376", 660, 7},
            {1562, "Customer490", 1450, 25},
            {1185, "Customer165", 470, 11},
            {1431, "Customer311", 1020, 30},
            {1302, "Customer564", 600, 18},
            {1521, "Customer228", 1250, 9},
            {1223, "Customer405", 560, 13},

            {1147, "Customer189", 390, 24},
            {1466, "Customer347", 1290, 5},
            {1388, "Customer522", 910, 17},
            {1267, "Customer283", 750, 1},
            {1599, "Customer419", 1590, 22},
            {1163, "Customer106", 430, 28},
            {1404, "Customer550", 960, 8},
            {1322, "Customer241", 640, 31},
            {1550, "Customer383", 1410, 12},
            {1281, "Customer469", 730, 3},

            {1105, "Customer271", 330, 16},
            {1503, "Customer515", 1160, 26},
            {1344, "Customer134", 700, 19},
            {1215, "Customer399", 540, 10},
            {1571, "Customer455", 1480, 6},
            {1195, "Customer208", 500, 23},
            {1449, "Customer581", 1330, 15},
            {1311, "Customer169", 610, 29},
            {1537, "Customer327", 1370, 1},
            {1239, "Customer483", 880, 21},

            {1139, "Customer247", 370, 7},
            {1481, "Customer401", 1230, 14},
            {1361, "Customer594", 760, 25},
            {1251, "Customer152", 670, 12},
            {1583, "Customer316", 1510, 30},
            {1172, "Customer488", 440, 18},
            {1417, "Customer219", 1010, 4},
            {1296, "Customer555", 810, 27},
            {1515, "Customer111", 1220, 9},
            {1339, "Customer361", 690, 20},

            {1111, "Customer439", 350, 11},
            {1462, "Customer262", 1100, 2},
            {1381, "Customer527", 900, 24},
            {1228, "Customer195", 570, 17},
            {1566, "Customer355", 1460, 31},
            {1151, "Customer501", 400, 5},
            {1437, "Customer144", 1040, 13},
            {1307, "Customer425", 610, 22},
            {1548, "Customer288", 1400, 8},
            {1272, "Customer577", 720, 29},

            {1128, "Customer172", 360, 15},
            {1499, "Customer339", 1190, 26},
            {1394, "Customer561", 930, 3},
            {1261, "Customer222", 740, 10},
            {1596, "Customer447", 1570, 23},
            {1181, "Customer131", 460, 19},
            {1427, "Customer508", 1030, 6},
            {1328, "Customer294", 650, 28},
            {1527, "Customer416", 1270, 1},
            {1241, "Customer158", 650, 12},

            {1101, "Customer350", 300, 21},
            {1451, "Customer585", 1070, 14},
            {1356, "Customer216", 750, 30},
            {1206, "Customer493", 520, 25},
            {1574, "Customer141", 1500, 7},
            {1168, "Customer371", 420, 16},
            {1408, "Customer544", 970, 11},
            {1286, "Customer277", 770, 4},
            {1541, "Customer409", 1380, 18},
            {1372, "Customer198", 860, 9},

            {1143, "Customer329", 380, 2},
            {1476, "Customer558", 1240, 27},
            {1331, "Customer205", 630, 20},
            {1231, "Customer472", 590, 13},
            {1553, "Customer117", 1430, 31},
            {1199, "Customer381", 500, 5},
            {1441, "Customer529", 1060, 24},
            {1319, "Customer251", 630, 8},
            {1510, "Customer434", 1200, 17},
            {1263, "Customer161", 720, 29},

            {1119, "Customer466", 340, 10},
            {1491, "Customer280", 1170, 22},
            {1385, "Customer512", 910, 15},
            {1219, "Customer137", 550, 1},
            {1585, "Customer422", 1530, 26},
            {1159, "Customer291", 410, 19},
            {1434, "Customer575", 1030, 12},
            {1299, "Customer183", 800, 30},
            {1530, "Customer451", 1350, 6},
            {1364, "Customer302", 770, 23},

            {1131, "Customer591", 360, 14},
            {1455, "Customer236", 1090, 7},
            {1347, "Customer480", 720, 28},
            {1248, "Customer124", 660, 11},
            {1559, "Customer392", 1440, 21},
            {1175, "Customer567", 450, 4},
            {1414, "Customer258", 1000, 16},
            {1275, "Customer441", 730, 25},
            {1518, "Customer174", 1230, 18},
            {1391, "Customer325", 920, 9},

            {1108, "Customer505", 330, 31},
            {1474, "Customer210", 1130, 2},
            {1375, "Customer458", 850, 20},
            {1225, "Customer147", 570, 13},
            {1590, "Customer344", 1550, 27},
            {1187, "Customer537", 480, 8},
            {1425, "Customer285", 1040, 24},
            {1305, "Customer495", 600, 15},
            {1546, "Customer192", 1390, 5},
            {1253, "Customer364", 680, 29},

            {1153, "Customer525", 400, 12},
            {1501, "Customer265", 1160, 1},
            {1336, "Customer415", 640, 23},
            {1213, "Customer109", 530, 10},
            {1568, "Customer373", 1470, 26},
            {1165, "Customer547", 420, 17},
            {1447, "Customer225", 1080, 3},
            {1283, "Customer428", 760, 19},
            {1524, "Customer150", 1260, 30},
            {1359, "Customer319", 760, 6},

            {1137, "Customer460", 370, 22},
            {1486, "Customer244", 1140, 15},
            {1397, "Customer553", 940, 8},
            {1259, "Customer179", 700, 28},
            {1580, "Customer337", 1510, 11},
            {1193, "Customer597", 490, 4},
            {1420, "Customer203", 1020, 25},
            {1293, "Customer436", 810, 14},
            {1535, "Customer127", 1360, 20},
            {1325, "Customer386", 650, 1},

            {1109, "Customer517", 340, 9},
            {1469, "Customer274", 1110, 31},
            {1353, "Customer403", 730, 13},
            {1237, "Customer167", 600, 24},
            {1564, "Customer358", 1450, 7},
            {1179, "Customer531", 460, 17},
            {1402, "Customer231", 950, 3},
            {1280, "Customer485", 710, 29},
            {1552, "Customer113", 1410, 12},
            {1383, "Customer341", 890, 19},

            {1145, "Customer579", 390, 5},
            {1497, "Customer297", 1190, 26},
            {1369, "Customer453", 790, 16},
            {1208, "Customer185", 540, 2},
            {1594, "Customer411", 1580, 22},
            {1161, "Customer569", 430, 10},
            {1439, "Customer249", 1050, 30},
            {1309, "Customer478", 620, 15},
            {1523, "Customer139", 1260, 27},
            {1270, "Customer331", 740, 8},

            {1125, "Customer496", 350, 1},
            {1457, "Customer214", 1090, 23},
            {1341, "Customer421", 670, 14},
            {1221, "Customer157", 560, 11},
            {1573, "Customer305", 1500, 29},
            {1183, "Customer543", 470, 18},
            {1413, "Customer282", 990, 4},
            {1288, "Customer464", 780, 25},
            {1513, "Customer101", 1210, 13},
            {1371, "Customer369", 830, 20},

            {1113, "Customer521", 350, 6},
            {1483, "Customer239", 1130, 28},
            {1389, "Customer443", 920, 15},
            {1243, "Customer191", 660, 3},
            {1587, "Customer321", 1530, 22},
            {1197, "Customer563", 500, 10},
            {1433, "Customer273", 1030, 31},
            {1301, "Customer407", 590, 17},
            {1543, "Customer143", 1390, 24},
            {1257, "Customer353", 690, 8},

            {1149, "Customer587", 400, 12},
            {1500, "Customer211", 1200, 29},
            {1363, "Customer499", 760, 19},
            {1217, "Customer123", 550, 5},
            {1560, "Customer391", 1430, 26},
            {1169, "Customer513", 420, 14},
            {1406, "Customer293", 970, 1},
            {1285, "Customer471", 770, 23},
            {1519, "Customer163", 1230, 16},
            {1373, "Customer333", 860, 30},

            {1133, "Customer481", 370, 7},
            {1473, "Customer252", 1120, 25},
            {1393, "Customer539", 930, 18},
            {1249, "Customer171", 670, 9},
            {1591, "Customer313", 1560, 2},
            {1189, "Customer557", 490, 21},
            {1421, "Customer227", 1020, 13},
            {1295, "Customer449", 810, 31},
            {1539, "Customer105", 1370, 11},
            {1329, "Customer363", 650, 27},

            {1107, "Customer417", 320, 4},
            {1465, "Customer269", 1110, 20},
            {1349, "Customer523", 730, 15},
            {1229, "Customer119", 580, 24},
            {1565, "Customer349", 1460, 6},
            {1173, "Customer583", 440, 17},
            {1419, "Customer243", 1010, 3},
            {1282, "Customer433", 760, 28},
            {1547, "Customer181", 1400, 12},
            {1387, "Customer307", 900, 19},

            {1121, "Customer451", 340, 30},
            {1493, "Customer221", 1180, 8},
            {1377, "Customer507", 870, 23},
            {1265, "Customer133", 710, 16},
            {1598, "Customer387", 1590, 1},
            {1157, "Customer551", 410, 14},
            {1436, "Customer290", 1040, 25},
            {1306, "Customer413", 610, 10},
            {1529, "Customer159", 1340, 22},
            {1246, "Customer379", 660, 7},

            {1117, "Customer535", 340, 13},
            {1453, "Customer287", 1080, 2},
            {1337, "Customer463", 640, 24},
            {1204, "Customer107", 530, 17},
            {1576, "Customer323", 1500, 31},
            {1167, "Customer599", 420, 5},
            {1409, "Customer256", 980, 19},
            {1277, "Customer487", 730, 9},
            {1511, "Customer129", 1210, 28},
            {1357, "Customer343", 750, 15},

            {1141, "Customer565", 380, 27},
            {1479, "Customer202", 1220, 11},
            {1396, "Customer491", 940, 4},
            {1260, "Customer145", 700, 22},
            {1581, "Customer377", 1520, 16},
            {1194, "Customer521", 490, 3},
            {1424, "Customer235", 1040, 26},
            {1294, "Customer411", 810, 12},
            {1536, "Customer199", 1360, 30},
            {1327, "Customer317", 650, 8},

            {1104, "Customer447", 310, 21},
            {1468, "Customer279", 1100, 14},
            {1354, "Customer515", 740, 6},
            {1238, "Customer131", 600, 29},
            {1567, "Customer351", 1460, 17},
            {1180, "Customer585", 460, 2},
            {1403, "Customer213", 950, 23},
            {1284, "Customer473", 760, 10},
            {1554, "Customer165", 1420, 31},
            {1384, "Customer397", 890, 13},

            {1146, "Customer541", 390, 25},
            {1498, "Customer263", 1190, 9},
            {1370, "Customer435", 790, 18},
            {1209, "Customer101", 540, 4},
            {1595, "Customer329", 1580, 27},
            {1162, "Customer519", 430, 15},
            {1440, "Customer207", 1060, 1},
            {1310, "Customer493", 620, 22},
            {1525, "Customer125", 1260, 11},
            {1271, "Customer389", 740, 30},

            {1127, "Customer563", 360, 7},
            {1458, "Customer289", 1090, 24},
            {1342, "Customer477", 670, 16},
            {1222, "Customer149", 560, 5},
            {1575, "Customer361", 1500, 28},
            {1184, "Customer533", 470, 19},
            {1415, "Customer255", 1000, 12},
            {1289, "Customer423", 780, 3},
            {1514, "Customer115", 1220, 26},
            {1374, "Customer301", 830, 14},

            {1114, "Customer487", 330, 20},
            {1484, "Customer231", 1140, 11},
            {1390, "Customer571", 920, 2},
            {1244, "Customer183", 660, 27},
            {1589, "Customer347", 1540, 15},
            {1198, "Customer509", 500, 8},
            {1435, "Customer241", 1040, 30},
            {1303, "Customer469", 600, 23},
            {1545, "Customer153", 1390, 6},
            {1258, "Customer325", 690, 17},

            {1150, "Customer555", 400, 13},
            {1502, "Customer219", 1200, 29},
            {1365, "Customer401", 770, 22},
            {1218, "Customer111", 550, 4},
            {1561, "Customer395", 1430, 25},
            {1170, "Customer527", 420, 10},
            {1407, "Customer261", 970, 18},
            {1287, "Customer415", 770, 31},
            {1520, "Customer177", 1240, 1},
            {1375, "Customer357", 860, 16},

            {1135, "Customer431", 370, 8},
            {1475, "Customer299", 1130, 24},
            {1395, "Customer567", 940, 15},
            {1250, "Customer161", 670, 3},
            {1593, "Customer381", 1580, 26},
            {1191, "Customer549", 490, 12},
            {1422, "Customer215", 1020, 30},
            {1297, "Customer457", 810, 7},
            {1540, "Customer109", 1370, 21},
            {1330, "Customer327", 650, 19},

            {1109, "Customer511", 320, 5},
            {1467, "Customer233", 1100, 22},
            {1350, "Customer497", 730, 14},
            {1230, "Customer127", 580, 27},
            {1566, "Customer311", 1460, 11},
            {1174, "Customer575", 440, 18},
            {1420, "Customer251", 1010, 9},
            {1283, "Customer461", 760, 30},
            {1549, "Customer137", 1400, 13},
            {1389, "Customer303", 910, 2},

            {1123, "Customer429", 350, 28},
            {1494, "Customer283", 1180, 16},
            {1379, "Customer547", 870, 6},
            {1266, "Customer175", 710, 23},
            {1599, "Customer319", 1590, 1},
            {1158, "Customer503", 410, 15},
            {1438, "Customer267", 1050, 29},
            {1308, "Customer439", 610, 12},
            {1531, "Customer151", 1350, 20},
            {1247, "Customer367", 660, 8},

            {1118, "Customer593", 340, 17},
            {1454, "Customer205", 1080, 4},
            {1338, "Customer445", 640, 25},
            {1205, "Customer115", 530, 14},
            {1578, "Customer383", 1510, 31},
            {1168, "Customer529", 420, 11},
            {1410, "Customer271", 980, 22},
            {1279, "Customer419", 730, 9},
            {1512, "Customer189", 1210, 30},
            {1358, "Customer355", 750, 13},

            {1142, "Customer531", 380, 26},
            {1480, "Customer247", 1220, 12},
            {1398, "Customer405", 940, 5},
            {1262, "Customer155", 700, 24},
            {1582, "Customer322", 1520, 18},
            {1196, "Customer577", 490, 7},
            {1426, "Customer294", 1040, 29},
            {1295, "Customer483", 810, 16},
            {1538, "Customer169", 1370, 1},
            {1328, "Customer341", 650, 21},

            {1106, "Customer499", 310, 23},
            {1470, "Customer212", 1110, 10},
            {1355, "Customer425", 740, 8},
            {1240, "Customer139", 600, 27},
            {1569, "Customer369", 1470, 15},
            {1182, "Customer501", 460, 4},
            {1405, "Customer228", 960, 19},
            {1285, "Customer455", 760, 31},
            {1555, "Customer111", 1420, 14},
            {1385, "Customer331", 890, 11},

            {1148, "Customer555", 390, 30},
            {1499, "Customer285", 1190, 13},
            {1372, "Customer413", 800, 25},
            {1210, "Customer177", 540, 6},
            {1596, "Customer305", 1580, 18},
            {1164, "Customer581", 430, 22},
            {1442, "Customer241", 1060, 9},
            {1312, "Customer463", 620, 17},
            {1526, "Customer149", 1270, 2},
            {1273, "Customer311", 740, 28},

            {1129, "Customer517", 360, 12},
            {1459, "Customer261", 1100, 21},
            {1343, "Customer433", 680, 5},
            {1224, "Customer181", 560, 16},
            {1576, "Customer357", 1510, 27},
            {1186, "Customer525", 470, 10},
            {1416, "Customer239", 1000, 31},
            {1290, "Customer401", 780, 14},
            {1516, "Customer134", 1220, 24},
            {1376, "Customer399", 840, 8},

            {1116, "Customer453", 330, 19},
            {1485, "Customer215", 1140, 13},
            {1391, "Customer591", 920, 6},
            {1245, "Customer165", 670, 25},
            {1590, "Customer327", 1540, 18},
            {1199, "Customer553", 500, 2},
            {1437, "Customer281", 1040, 29},
            {1304, "Customer419", 600, 11},
            {1546, "Customer103", 1390, 22},
            {1259, "Customer387", 690, 15},

            {1152, "Customer571", 400, 7},
            {1503, "Customer227", 1200, 30},
            {1366, "Customer487", 770, 17},
            {1220, "Customer133", 550, 9},
            {1562, "Customer347", 1440, 24},
            {1171, "Customer509", 420, 13},
            {1408, "Customer291", 970, 1},
            {1288, "Customer443", 770, 28},
            {1521, "Customer155", 1240, 4},
            {1376, "Customer319", 860, 21},

            {1136, "Customer405", 370, 14},
            {1476, "Customer201", 1130, 26},
            {1396, "Customer511", 940, 8},
            {1251, "Customer195", 670, 19},
            {1594, "Customer333", 1580, 31},
            {1192, "Customer583", 490, 5},
            {1423, "Customer267", 1020, 22},
            {1298, "Customer421", 820, 12},
            {1541, "Customer129", 1370, 30},
            {1331, "Customer355", 650, 16},

            {1110, "Customer597", 320, 2},
            {1468, "Customer249", 1100, 27},
            {1351, "Customer411", 730, 10},
            {1231, "Customer117", 590, 23},
            {1567, "Customer376", 1460, 15},
            {1175, "Customer529", 440, 8},
            {1421, "Customer256", 1010, 29},
            {1284, "Customer481", 760, 1},
            {1550, "Customer145", 1410, 18},
            {1390, "Customer322", 910, 25},

            {1124, "Customer431", 350, 13},
            {1495, "Customer279", 1180, 30},
            {1380, "Customer565", 880, 22},
            {1267, "Customer169", 710, 4},
            {1600, "Customer343", 1600, 17},
            {1159, "Customer533", 410, 9},
            {1439, "Customer213", 1050, 26},
            {1309, "Customer495", 620, 11},
            {1532, "Customer141", 1350, 15},
            {1248, "Customer317", 660, 28},

            {1119, "Customer547", 340, 6},
            {1455, "Customer289", 1080, 20},
            {1339, "Customer409", 640, 14},
            {1206, "Customer131", 530, 31},
            {1579, "Customer350", 1510, 7},
            {1169, "Customer558", 420, 24},
            {1411, "Customer205", 990, 12},
            {1280, "Customer447", 730, 3},
            {1513, "Customer163", 1210, 19},
            {1359, "Customer381", 750, 27},

            {1143, "Customer503", 380, 10},
            {1481, "Customer255", 1220, 16},
            {1399, "Customer469", 940, 1},
            {1263, "Customer151", 700, 25},
            {1583, "Customer391", 1520, 13},
            {1197, "Customer513", 500, 29},
            {1427, "Customer277", 1040, 8},
            {1296, "Customer451", 810, 22},
            {1539, "Customer174", 1370, 5},
            {1329, "Customer313", 650, 18},

            {1107, "Customer483", 310, 14},
            {1471, "Customer221", 1110, 30},
            {1356, "Customer461", 740, 21},
            {1241, "Customer121", 600, 7},
            {1570, "Customer301", 1470, 26},
            {1183, "Customer587", 470, 11},
            {1406, "Customer236", 960, 15},
            {1286, "Customer472", 770, 4},
            {1556, "Customer152", 1420, 23},
            {1386, "Customer334", 890, 9},

            {1149, "Customer522", 400, 28},
            {1500, "Customer293", 1190, 12},
            {1373, "Customer441", 800, 20},
            {1211, "Customer189", 540, 31},
            {1597, "Customer325", 1590, 16},
            {1165, "Customer515", 430, 6},
            {1443, "Customer254", 1060, 25},
            {1313, "Customer401", 620, 13},
            {1527, "Customer111", 1270, 2},
            {1274, "Customer367", 740, 17},

            {1130, "Customer594", 360, 10},
            {1460, "Customer216", 1100, 24},
            {1344, "Customer449", 680, 15},
            {1225, "Customer134", 570, 8},
            {1577, "Customer349", 1510, 29},
            {1187, "Customer551", 480, 22},
            {1417, "Customer212", 1010, 1},
            {1291, "Customer428", 780, 19},
            {1517, "Customer192", 1220, 14},
            {1377, "Customer389", 840, 30},

            {1117, "Customer415", 330, 7},
            {1486, "Customer258", 1140, 26},
            {1392, "Customer537", 920, 12},
            {1246, "Customer106", 660, 21},
            {1591, "Customer379", 1550, 18},
            {1200, "Customer564", 500, 5},
            {1438, "Customer228", 1050, 13},
            {1305, "Customer462", 600, 31},
            {1547, "Customer128", 1400, 9},
            {1260, "Customer308", 690, 23},

            {2345, "Customer714", 890, 14},
            {2556, "Customer628", 1420, 3},
            {2102, "Customer989", 310, 22},
            {2489, "Customer772", 1150, 29},
            {2315, "Customer801", 620, 7},
            {2178, "Customer645", 450, 1},
            {2592, "Customer903", 1580, 18},
            {2367, "Customer734", 780, 25},
            {2211, "Customer919", 510, 12},
            {2445, "Customer667", 1310, 30},

            {2512, "Customer755", 1210, 5},
            {2134, "Customer898", 380, 19},
            {2399, "Customer612", 940, 26},
            {2278, "Customer767", 710, 8},
            {2577, "Customer988", 1490, 11},
            {2190, "Customer714", 490, 31},
            {2423, "Customer831", 1050, 15},
            {2333, "Customer699", 680, 2},
            {2471, "Customer904", 1280, 24},
            {2256, "Customer787", 830, 17},

            {2115, "Customer722", 340, 9},
            {2533, "Customer941", 1360, 28},
            {2351, "Customer749", 740, 13},
            {2202, "Customer876", 530, 6},
            {2588, "Customer633", 1540, 21},
            {2411, "Customer795", 990, 4},
            {2156, "Customer911", 410, 23},
            {2459, "Customer777", 1120, 16},
            {2290, "Customer708", 790, 10},
            {2378, "Customer862", 870, 27},

            {2544, "Customer654", 1390, 2},
            {2122, "Customer933", 320, 20},
            {2495, "Customer721", 1180, 14},
            {2245, "Customer776", 660, 7},
            {2562, "Customer890", 1450, 25},
            {2185, "Customer765", 470, 11},
            {2431, "Customer711", 1020, 30},
            {2302, "Customer964", 600, 18},
            {2521, "Customer628", 1250, 9},
            {2223, "Customer805", 560, 13},

            {2147, "Customer789", 390, 24},
            {2466, "Customer747", 1290, 5},
            {2388, "Customer922", 910, 17},
            {2267, "Customer683", 750, 1},
            {2599, "Customer819", 1590, 22},
            {2163, "Customer706", 430, 28},
            {2404, "Customer950", 960, 8},
            {2322, "Customer641", 640, 31},
            {2550, "Customer783", 1410, 12},
            {2281, "Customer869", 730, 3},

            {2105, "Customer671", 330, 16},
            {2503, "Customer915", 1160, 26},
            {2344, "Customer734", 700, 19},
            {2215, "Customer799", 540, 10},
            {2571, "Customer855", 1480, 6},
            {2195, "Customer608", 500, 23},
            {2449, "Customer981", 1330, 15},
            {2311, "Customer769", 610, 29},
            {2537, "Customer727", 1370, 1},
            {2239, "Customer883", 880, 21},

            {2139, "Customer647", 370, 7},
            {2481, "Customer801", 1230, 14},
            {2361, "Customer994", 760, 25},
            {2251, "Customer752", 670, 12},
            {2583, "Customer716", 1510, 30},
            {2172, "Customer888", 440, 18},
            {2417, "Customer619", 1010, 4},
            {2296, "Customer955", 810, 27},
            {2515, "Customer711", 1220, 9},
            {2339, "Customer761", 690, 20},

            {2111, "Customer839", 350, 11},
            {2462, "Customer662", 1100, 2},
            {2381, "Customer927", 900, 24},
            {2228, "Customer795", 570, 17},
            {2566, "Customer755", 1460, 31},
            {2151, "Customer901", 400, 5},
            {2437, "Customer744", 1040, 13},
            {2307, "Customer825", 610, 22},
            {2548, "Customer688", 1400, 8},
            {2272, "Customer977", 720, 29},

            {2128, "Customer712", 360, 15},
            {2499, "Customer839", 1190, 26},
            {2394, "Customer961", 930, 3},
            {2261, "Customer622", 740, 10},
            {2596, "Customer847", 1570, 23},
            {2181, "Customer731", 460, 19},
            {2427, "Customer908", 1030, 6},
            {2328, "Customer694", 650, 28},
            {2527, "Customer816", 1270, 1},
            {2241, "Customer758", 650, 12},

            {2101, "Customer750", 300, 21},
            {2451, "Customer985", 1070, 14},
            {2356, "Customer616", 750, 30},
            {2206, "Customer893", 520, 25},
            {2574, "Customer741", 1500, 7},
            {2168, "Customer771", 420, 16},
            {2408, "Customer944", 970, 11},
            {2286, "Customer677", 770, 4},
            {2541, "Customer809", 1380, 18},
            {2372, "Customer798", 860, 9},

            {2143, "Customer729", 380, 2},
            {2476, "Customer958", 1240, 27},
            {2331, "Customer605", 630, 20},
            {2231, "Customer872", 590, 13},
            {2553, "Customer717", 1430, 31},
            {2199, "Customer781", 500, 5},
            {2441, "Customer929", 1060, 24},
            {2319, "Customer651", 630, 8},
            {2510, "Customer834", 1200, 17},
            {2263, "Customer761", 720, 29},

            {2119, "Customer866", 340, 10},
            {2491, "Customer680", 1170, 22},
            {2385, "Customer912", 910, 15},
            {2219, "Customer737", 550, 1},
            {2585, "Customer822", 1530, 26},
            {2159, "Customer691", 410, 19},
            {2434, "Customer975", 1030, 12},
            {2299, "Customer783", 800, 30},
            {2530, "Customer851", 1350, 6},
            {2364, "Customer702", 770, 23},

            {2131, "Customer991", 360, 14},
            {2455, "Customer636", 1090, 7},
            {2347, "Customer880", 720, 28},
            {2248, "Customer724", 660, 11},
            {2559, "Customer792", 1440, 21},
            {2175, "Customer967", 450, 4},
            {2414, "Customer658", 1000, 16},
            {2275, "Customer841", 730, 25},
            {2518, "Customer774", 1230, 18},
            {2391, "Customer725", 920, 9},

            {2108, "Customer905", 330, 31},
            {2474, "Customer610", 1130, 2},
            {2375, "Customer858", 850, 20},
            {2225, "Customer747", 570, 13},
            {2590, "Customer744", 1550, 27},
            {2187, "Customer937", 480, 8},
            {2425, "Customer685", 1040, 24},
            {2305, "Customer895", 600, 15},
            {2546, "Customer792", 1390, 5},
            {2253, "Customer764", 680, 29},

            {2153, "Customer925", 400, 12},
            {2501, "Customer665", 1160, 1},
            {2336, "Customer815", 640, 23},
            {2213, "Customer709", 530, 10},
            {2568, "Customer773", 1470, 26},
            {2165, "Customer947", 420, 17},
            {2447, "Customer625", 1080, 3},
            {2283, "Customer828", 760, 19},
            {2524, "Customer750", 1260, 30},
            {2359, "Customer719", 760, 6},

            {2137, "Customer860", 370, 22},
            {2486, "Customer644", 1140, 15},
            {2397, "Customer953", 940, 8},
            {2259, "Customer779", 700, 28},
            {2580, "Customer737", 1510, 11},
            {2193, "Customer997", 490, 4},
            {2420, "Customer603", 1020, 25},
            {2293, "Customer836", 810, 14},
            {2535, "Customer727", 1360, 20},
            {2325, "Customer786", 650, 1},

            {2109, "Customer917", 340, 9},
            {2469, "Customer674", 1110, 31},
            {2353, "Customer803", 730, 13},
            {2237, "Customer767", 600, 24},
            {2564, "Customer758", 1450, 7},
            {2179, "Customer931", 460, 17},
            {2402, "Customer631", 950, 3},
            {2280, "Customer885", 710, 29},
            {2552, "Customer713", 1410, 12},
            {2383, "Customer741", 890, 19},

            {2145, "Customer979", 390, 5},
            {2497, "Customer697", 1190, 26},
            {2369, "Customer853", 790, 16},
            {2208, "Customer785", 540, 2},
            {2594, "Customer811", 1580, 22},
            {2161, "Customer969", 430, 10},
            {2439, "Customer649", 1050, 30},
            {2309, "Customer878", 620, 15},
            {2523, "Customer739", 1260, 27},
            {2270, "Customer731", 740, 8},

            {2125, "Customer896", 350, 1},
            {2457, "Customer614", 1090, 23},
            {2341, "Customer821", 670, 14},
            {2221, "Customer757", 560, 11},
            {2573, "Customer705", 1500, 29},
            {2183, "Customer943", 470, 18},
            {2413, "Customer682", 990, 4},
            {2288, "Customer864", 780, 25},
            {2513, "Customer701", 1210, 13},
            {2371, "Customer769", 830, 20},

            {2113, "Customer921", 350, 6},
            {2483, "Customer639", 1130, 28},
            {2389, "Customer843", 920, 15},
            {2243, "Customer791", 660, 3},
            {2587, "Customer721", 1530, 22},
            {2197, "Customer963", 500, 10},
            {2433, "Customer673", 1030, 31},
            {2301, "Customer807", 590, 17},
            {2543, "Customer743", 1390, 24},
            {2257, "Customer753", 690, 8},
        };

    Booking workingBooking[SIZE];
    int choice;

    for (int i = 0; i < SIZE; i++)
    {
        workingBooking[i] = originalBooking[i];
    }

    do
    {
        cout << "\nHotel Booking Records\n";
        cout << "1. Display Records." << endl;
        cout << "2. Heap Sort" << endl;
        cout << "3. Shell Sort" << endl;
        cout << "4. Step-by-step Algorithm Demonstration" << endl;
        cout << "5. Compare Performance (Best Case, Average Case, and Worst Case)" << endl;
        cout << "6. Exit Program" << endl;
        cout << "Enter choice: ";
        cin >> choice;

        // displays hotel booking records
        if (choice == 1)
        {
            displayBookings(workingBooking, SIZE);
        }

        else if (choice == 2)
        {
            for (int i = 0; i < SIZE; i++)
            {
                workingBooking[i] = originalBooking[i];
            }

            cout << "\n=== Running Heap Sort with 700 Booking Records ===" << endl;
            algorithm(workingBooking, SIZE, 1);
        }

        else if (choice == 3)
        {
            for (int i = 0; i < SIZE; i++)
            {
                workingBooking[i] = originalBooking[i];
            }

            cout << "\n=== Running Shell Sort with 700 Booking Records ===" << endl;
            algorithm(workingBooking, SIZE, 2);
        }

        else if (choice == 4)
        {
            const int DEMO_SIZE = 100;
            Booking demoDataArray1[DEMO_SIZE] = {
                /*Demo Customer*/
                {1028, "DC1", 500, 1},
                {1063, "DC2", 600, 2},
                {1005, "DC3", 700, 3},
                {1012, "DC4", 800, 4},
                {1087, "DC5", 900, 5},
                {1041, "DC6", 400, 6},
                {1074, "DC7", 300, 7},
                {1099, "DC8", 900, 8},
                {1037, "DC9", 400, 9},
                {1048, "DC10", 300, 10},

                {1115, "DC11", 450, 11},
                {1142, "DC12", 550, 12},
                {1103, "DC13", 750, 13},
                {1189, "DC14", 850, 14},
                {1127, "DC15", 950, 15},
                {1164, "DC16", 350, 16},
                {1151, "DC17", 250, 17},
                {1176, "DC18", 1000, 18},
                {1133, "DC19", 650, 19},
                {1190, "DC20", 1100, 20},

                {1204, "DC21", 500, 21},
                {1255, "DC22", 600, 22},
                {1211, "DC23", 700, 23},
                {1238, "DC24", 800, 24},
                {1282, "DC25", 900, 25},
                {1247, "DC26", 400, 26},
                {1279, "DC27", 300, 27},
                {1223, "DC28", 1200, 28},
                {1266, "DC29", 450, 29},
                {1291, "DC30", 1300, 30},

                {1318, "DC31", 550, 31},
                {1362, "DC32", 650, 32},
                {1304, "DC33", 750, 33},
                {1349, "DC34", 850, 34},
                {1385, "DC35", 950, 35},
                {1331, "DC36", 350, 36},
                {1370, "DC37", 250, 37},
                {1394, "DC38", 1400, 38},
                {1325, "DC39", 700, 39},
                {1357, "DC40", 1500, 40},

                {1412, "DC41", 500, 41},
                {1467, "DC42", 600, 42},
                {1409, "DC43", 700, 43},
                {1433, "DC44", 800, 44},
                {1481, "DC45", 900, 45},
                {1424, "DC46", 400, 46},
                {1476, "DC47", 300, 47},
                {1495, "DC48", 1150, 48},
                {1448, "DC49", 550, 49},
                {1453, "DC50", 1250, 50},

                {1502, "DC51", 450, 51},
                {1554, "DC52", 650, 52},
                {1519, "DC53", 750, 53},
                {1531, "DC54", 850, 54},
                {1588, "DC55", 950, 55},
                {1542, "DC56", 350, 56},
                {1573, "DC57", 250, 57},
                {1596, "DC58", 1050, 58},
                {1527, "DC59", 550, 59},
                {1561, "DC60", 1350, 60},

                {1614, "DC61", 500, 61},
                {1663, "DC62", 600, 62},
                {1607, "DC63", 700, 63},
                {1629, "DC64", 800, 64},
                {1681, "DC65", 900, 65},
                {1645, "DC66", 400, 66},
                {1672, "DC67", 300, 67},
                {1698, "DC68", 1250, 68},
                {1634, "DC69", 450, 69},
                {1650, "DC70", 1450, 70},

                {1711, "DC71", 550, 71},
                {1769, "DC72", 650, 72},
                {1703, "DC73", 750, 73},
                {1742, "DC74", 850, 74},
                {1786, "DC75", 950, 75},
                {1735, "DC76", 350, 76},
                {1770, "DC77", 250, 77},
                {1794, "DC78", 1100, 78},
                {1721, "DC79", 600, 79},
                {1758, "DC80", 1500, 80},

                {1819, "DC81", 400, 81},
                {1862, "DC82", 700, 82},
                {1805, "DC83", 800, 83},
                {1833, "DC84", 900, 84},
                {1884, "DC85", 1000, 85},
                {1841, "DC86", 450, 86},
                {1877, "DC87", 350, 87},
                {1899, "DC88", 1300, 88},
                {1826, "DC89", 500, 89},
                {1852, "DC90", 1200, 90},

                {1913, "DC91", 600, 91},
                {1967, "DC92", 750, 92},
                {1904, "DC93", 850, 93},
                {1938, "DC94", 950, 94},
                {1981, "DC95", 1050, 95},
                {1925, "DC96", 550, 96},
                {1970, "DC97", 400, 97},
                {1992, "DC98", 1400, 98},
                {1946, "DC99", 650, 99},
                {1959, "DC100", 1350, 100},
            };
            Booking demoDataArray2[DEMO_SIZE];
            for (int i = 0; i < DEMO_SIZE; i++)
            {
                demoDataArray2[i] = demoDataArray1[i];
            }

            int demoChoice;
            cout << "\nStep-by-step algorithm demonstration" << endl;
            cout << "1. Demo Heap Sort" << endl;
            cout << "2. Demo Shell Sort" << endl;
            cout << "Enter demo choice: ";
            cin >> demoChoice;

            if (demoChoice == 1)
            {
                cout << "\n=============================" << endl;
                cout << "Heap Sort Step-by-step algorithm demonstration" << endl;
                cout << "=============================" << endl;
                heapSortDemo(demoDataArray1, DEMO_SIZE);
            }

            else if (demoChoice == 2)
            {
                cout << "\n=============================" << endl;
                cout << "Shell Sort Step-by-step algorithm demonstration" << endl;
                cout << "=============================" << endl;
                shellSortDemo(demoDataArray2, DEMO_SIZE);
            }

            else
            {
                cout << "\nInvalid Selection!" << endl;
            }
        }

        else if (choice == 5)
        {
            Booking bestCase[SIZE];
            Booking worstCase[SIZE];
            Booking averageCase[SIZE];

            createBestCase(originalBooking, bestCase, SIZE);

            for (int i = 0; i < SIZE; i++)
            {
                averageCase[i] = originalBooking[i];
            }

            createWorstCase(bestCase, worstCase, SIZE);

            cout << "\n=============================" << endl;
            cout << "PERFORMANCE BENCHMARK (N=700)" << endl;
            cout << "=============================" << endl;
            cout << "\n=== HEAP SORT PERFORMANCE ===" << endl;
            cout << "-----------------------------" << endl;
            cout << "[Best Case]" << endl;
            algorithm(bestCase, SIZE, 1);
            cout << "\n[Average Case]" << endl;
            algorithm(averageCase, SIZE, 1);
            cout << "\n[Worst Case]" << endl;
            algorithm(worstCase, SIZE, 1);

            cout << "\n=== SHELL SORT PERFORMANCE ===" << endl;
            cout << "------------------------------" << endl;
            cout << "[Best Case]" << endl;
            algorithm(bestCase, SIZE, 2);
            cout << "\n[Average Case]" << endl;
            algorithm(averageCase, SIZE, 2);
            cout << "\n[Worst Case]" << endl;
            algorithm(worstCase, SIZE, 2);
            cout << "=============================" << endl;
        }

        else if (choice == 6)
        {
            cout << "\nExiting Program..." << endl;
            return 0;
        }

        else
        {
            cout << "\nInvalid Choice!" << endl;
        }
    } while (choice != 0);

    return 0;
}