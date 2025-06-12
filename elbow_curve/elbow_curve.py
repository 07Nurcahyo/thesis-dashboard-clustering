import pandas as pd

# Load the uploaded CSV file to analyze its contents
# file_path = 'data kesejahteraan pekerja - normalized.csv'
# file_path = 'elbow curve - hasil pra proses data.csv'
# file_path = 'k means data update.csv'
file_path = 'tes_elbow.csv'
data = pd.read_csv(file_path)
# Display the first few rows of the dataset to understand its structure
print(data.head(), data.shape)

# Replace commas with dots to convert the data to a proper numeric format
data = data.replace(',', '.', regex=True).astype(float)
# Verify the conversion
print("\n",data.head())


import matplotlib.pyplot as plt
from sklearn.cluster import KMeans

# Jarak k untuk di tes
k_values = range(2, 10)
# k_values = range(1, 11)

# Menghitung inertia untuk setiap k
inertia = []
for k in k_values:
    kmeans = KMeans(n_clusters=k, random_state=42)
    kmeans.fit(data)
    inertia.append(kmeans.inertia_)

# Plot elbow curve
# plt.figure(figsize=(8, 5))
plt.plot(k_values, inertia, marker='o', linestyle='--')
plt.title('Elbow Curve untuk K Optimal')
plt.xlabel('Clusters (k)')
plt.ylabel('Inertia')
plt.xticks(k_values)
plt.grid()
plt.show()






# import pandas as pd
# import matplotlib.pyplot as plt
# from sklearn.cluster import KMeans

# # Load CSV
# file_path = 'tes_elbow.csv'
# data = pd.read_csv(file_path)

# # Replace commas with dots and convert to float
# data = data.replace(',', '.', regex=True).astype(float)

# # Range k yang akan diuji
# k_values = range(2, 10)

# # Hitung inertia untuk setiap nilai k
# inertia = []
# for k in k_values:
#     kmeans = KMeans(n_clusters=k, random_state=42)
#     kmeans.fit(data)
#     inertia.append(kmeans.inertia_)

# # Cetak nilai inertia di terminal
# print("Nilai Inertia untuk tiap k:")
# for k, i in zip(k_values, inertia):
#     print(f"k = {k} -> Inertia = {i:.2f}")

# # Plot elbow curve
# plt.plot(k_values, inertia, marker='o', linestyle='--')
# plt.title('Elbow Curve untuk K Optimal')
# plt.xlabel('Clusters (k)')
# plt.ylabel('Inertia')
# plt.xticks(k_values)
# plt.grid()

# # Tambahkan nilai inertia di setiap titik
# for k, i in zip(k_values, inertia):
#     plt.text(k, i, f'{i:.2f}', ha='center', va='bottom', fontsize=9, color='blue')

# plt.show()