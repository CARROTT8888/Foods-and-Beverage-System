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

    comparisons++;
    // If left child is larger than root
    if (left < n && booking[left].bookingId > booking[largest].bookingId)
    {
        largest = left;
    }

    comparisons++;
    // If right child is larger than largest so far
    if (right < n && booking[right].bookingId > booking[largest].bookingId)
    {
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
            bool moved = false;

            for (j = i; j >= gap; j -= gap)
            {
                comparisons++;

                if (booking[j - gap].bookingId > temp.bookingId)
                {
                    booking[j] = booking[j - gap];
                    swapsCount++;
                    moved = true;
                }

                else
                {
                    break;
                }
            }

            // Place temp in its correct location
            booking[j] = temp;
            if (moved)
            {
                swapsCount++;
            }
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
            bool moved = false;
            for (j = i; j >= gap; j -= gap)
            {
                comparisons++;
                if (booking[j - gap].bookingId > temp.bookingId)
                {
                    booking[j] = booking[j - gap];
                    swapsCount++;
                    moved = true;
                }

                else
                {
                    break;
                }
            }
            booking[j] = temp;
            if (moved)
            {
                swapsCount++;
            }

            cout << "Step " << (step++) << " (Insert ID " << temp.bookingId << "): ";
            displayArray(booking, n);
        }
        cout << endl;
    }
}

void resetCounter()
{
    comparisons = 0;
    swapsCount = 0;
}

void createBestCase(Booking source[], Booking target[], int size)
{
    resetCounter();

    for (int i = 0; i < size; i++)
    {
        target[i] = source[i];
    }

    shellSort(target, size);

    resetCounter();
}

void createWorstCase(Booking bestCase[], Booking target[], int size)
{
    for (int i = 0; i < size; i++)
    {
        target[i] = bestCase[size - 1 - i];
    }
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

    const int SIZE = 100;

    // 100 hotel booking records
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

            cout << "\n=== Running Heap Sort with 100 Booking Records ===" << endl;
            algorithm(workingBooking, SIZE, 1);
        }

        else if (choice == 3)
        {
            for (int i = 0; i < SIZE; i++)
            {
                workingBooking[i] = originalBooking[i];
            }

            cout << "\n=== Running Shell Sort with 100 Booking Records ===" << endl;
            algorithm(workingBooking, SIZE, 2);
        }

        else if (choice == 4)
        {
            const int DEMO_SIZE = 10;
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
            Booking temp[SIZE];

            createBestCase(originalBooking, bestCase, SIZE);

            for (int i = 0; i < SIZE; i++)
            {
                averageCase[i] = originalBooking[i];
            }

            createWorstCase(bestCase, worstCase, SIZE);

            cout << "\n=============================" << endl;
            cout << "PERFORMANCE BENCHMARK (N=100)" << endl;
            cout << "=============================" << endl;
            cout << "\n=== HEAP SORT PERFORMANCE ===" << endl;
            cout << "-----------------------------" << endl;
            cout << "[Best Case]" << endl;
            for (int i = 0; i < SIZE; i++)
            {
                temp[i] = bestCase[i];
            }
            algorithm(temp, SIZE, 1);
            cout << "\n[Average Case]" << endl;
            for (int i = 0; i < SIZE; i++)
            {
                temp[i] = averageCase[i];
            }
            algorithm(temp, SIZE, 1);
            cout << "\n[Worst Case]" << endl;
            for (int i = 0; i < SIZE; i++)
            {
                temp[i] = worstCase[i];
            }
            algorithm(temp, SIZE, 1);

            cout << "\n=== SHELL SORT PERFORMANCE ===" << endl;
            cout << "------------------------------" << endl;
            cout << "[Best Case]" << endl;
            for (int i = 0; i < SIZE; i++)
            {
                temp[i] = bestCase[i];
            }
            algorithm(temp, SIZE, 2);
            cout << "\n[Average Case]" << endl;
            for (int i = 0; i < SIZE; i++)
            {
                temp[i] = averageCase[i];
            }
            algorithm(temp, SIZE, 2);
            cout << "\n[Worst Case]" << endl;
            for (int i = 0; i < SIZE; i++)
            {
                temp[i] = worstCase[i];
            }
            algorithm(temp, SIZE, 2);
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