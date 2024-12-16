import pandas as pd
import matplotlib.pyplot as plt
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler

## Membaca Data dan Preprocessing #
# Membaca file pengeluaran.csv #
pengeluaran = pd.read_csv('pengeluaran.csv')
print("5 data teratas : ")
print(pengeluaran.head())

# Menampilkan data kosong / Nan #
print("Jumlah data kosong per tabel : ")
print(pengeluaran.isnull().sum())

# Mengisi NaN dengan nilai rata-rata dari kolom 'mu' #
pengeluaran['peng'] = pengeluaran['peng'].fillna(pengeluaran['peng'].mean())

# Pilih kolom yang relevan untuk clustering (provinsi, peng)
data_pengeluaran = pengeluaran[['provinsi', 'peng']]
# Normalisasi data
scaler = StandardScaler()
data_pengeluaran_scaled = scaler.fit_transform(data_pengeluaran[['peng']])

# Menentukan Jumlah Cluster dengan Elbow Method #
# Menentukan rentang K yang ingin diuji #
K_range = range(1, 10)
inertia_peng = []
for k in K_range:
    kmeans = KMeans(n_clusters=k, random_state=42)
    kmeans.fit(data_pengeluaran_scaled)
    inertia_peng.append(kmeans.inertia_)
# Membuat plot elbow curve
plt.plot(K_range, inertia_peng, marker='o')
plt.title('Elbow Curve - Pengeluaran')
plt.xlabel('Jumlah Cluster (K)')
plt.ylabel('Inersia')
plt.show()