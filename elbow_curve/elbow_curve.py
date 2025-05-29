import pandas as pd

# Load the uploaded CSV file to analyze its contents
# file_path = 'data kesejahteraan pekerja - normalized.csv'
file_path = 'elbow curve - hasil pra proses data.csv'
# file_path = 'k means data update.csv'
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
k_values = range(1, 10)

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


