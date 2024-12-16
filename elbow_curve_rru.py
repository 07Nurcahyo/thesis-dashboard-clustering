import pandas as pd
import matplotlib.pyplot as plt
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler

## Membaca Data dan Preprocessing #
# Membaca file rataRataUpah.csv #
rata_rata_upah = pd.read_csv('rataRataUpah.csv')
print("5 data teratas : ")
print(rata_rata_upah.head())

# Menampilkan data kosong / Nan #
print("Jumlah data kosong per tabel : ")
print(rata_rata_upah.isnull().sum())

# Mengisi NaN dengan nilai rata-rata dari kolom 'mu' #
# rata_rata_upah['upah'] = rata_rata_upah['upah'].fillna(rata_rata_upah['upah'].mean())

# Pilih kolom yang relevan untuk clustering (provinsi, upah)
data_rru = rata_rata_upah[['provinsi', 'upah']]
# Normalisasi data
scaler = StandardScaler()
data_rru_scaled = scaler.fit_transform(data_rru[['upah']])

# Menentukan Jumlah Cluster dengan Elbow Method #
# Menentukan rentang K yang ingin diuji #
K_range = range(1, 10)
inertia_rru = []
for k in K_range:
    kmeans = KMeans(n_clusters=k, random_state=42)
    kmeans.fit(data_rru_scaled)
    inertia_rru.append(kmeans.inertia_)
# Membuat plot elbow curve
plt.plot(K_range, inertia_rru, marker='o')
plt.title('Elbow Curve - Pengeluaran')
plt.xlabel('Jumlah Cluster (K)')
plt.ylabel('Inersia')
plt.show()