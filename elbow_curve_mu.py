import pandas as pd
import matplotlib.pyplot as plt
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler

## Membaca Data dan Preprocessing #
# Membaca file minimalUpah.csv #
minimal_upah = pd.read_csv('minUpah.csv')
print("5 data teratas : ")
print(minimal_upah.head())

# Menampilkan data kosong / Nan #
print("Jumlah data kosong per tabel : ")
print(minimal_upah.isnull().sum())

# Mengisi NaN dengan nilai rata-rata dari kolom 'mu' #
minimal_upah['ump'] = minimal_upah['ump'].fillna(minimal_upah['ump'].mean())

# Pilih kolom yang relevan untuk clustering (provinsi, ump)
data_upah = minimal_upah[['provinsi', 'ump']]
# Normalisasi data
scaler = StandardScaler()
data_upah_scaled = scaler.fit_transform(data_upah[['ump']])

# Menentukan Jumlah Cluster dengan Elbow Method #
# Menentukan rentang K yang ingin diuji #
K_range = range(1, 10)
inertia_upah = []
for k in K_range:
    kmeans = KMeans(n_clusters=k, random_state=42)
    kmeans.fit(data_upah_scaled)
    inertia_upah.append(kmeans.inertia_)
# Membuat plot elbow curve
plt.plot(K_range, inertia_upah, marker='o')
plt.title('Elbow Curve - Minimum Upah')
plt.xlabel('Jumlah Cluster (K)')
plt.ylabel('Inersia')
plt.show()