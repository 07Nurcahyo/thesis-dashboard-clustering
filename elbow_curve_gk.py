import pandas as pd
import matplotlib.pyplot as plt
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler

## Membaca Data dan Preprocessing #
# Membaca file garisKemiskinan.csv #
garis_kemiskinan = pd.read_csv('garisKemiskinan.csv')
print("5 data teratas : ")
print(garis_kemiskinan.head())

# Menampilkan data kosong / Nan #
print("Jumlah data kosong per tabel : ")
print(garis_kemiskinan.isnull().sum())

# Mengisi NaN dengan nilai rata-rata dari kolom 'gk' #
garis_kemiskinan['gk'] = garis_kemiskinan['gk'].fillna(garis_kemiskinan['gk'].mean())

## Join per provinsi

## Pilih kolom yang relevan untuk clustering provinsi dan nilai garis kemiskinan (gk) #
data_kemiskinan = garis_kemiskinan[['provinsi', 'gk']]
# Menggunakan standar scaler untuk normalisasi (opsional) #
scaler = StandardScaler()
data_scaled = scaler.fit_transform(data_kemiskinan[['gk']])

# Menentukan Jumlah Cluster dengan Elbow Method #
# Menentukan rentang K yang ingin diuji #
K_range = range(1, 10)
inertia = []
# Melatih K-Means untuk setiap nilai K dan menghitung inersia #
for k in K_range:
    kmeans = KMeans(n_clusters=k, random_state=42)
    kmeans.fit(data_scaled)
    inertia.append(kmeans.inertia_)
# Membuat plot elbow curve
plt.plot(K_range, inertia, marker='o')
plt.title('Elbow Curve - Garis Kemiskinan')
plt.xlabel('Jumlah Cluster (K)')
plt.ylabel('Inersia')
plt.show()