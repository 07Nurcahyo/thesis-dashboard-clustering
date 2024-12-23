import pandas as pd

# Load the uploaded CSV file to analyze its contents
file_path = 'data kesejahteraan pekerja - normalized.csv'
data = pd.read_csv(file_path)
# Display the first few rows of the dataset to understand its structure
print(data.head(), data.shape)

# Replace commas with dots to convert the data to a proper numeric format
data = data.replace(',', '.', regex=True).astype(float)
# Verify the conversion
print("\n",data.head())


import matplotlib.pyplot as plt
from sklearn.cluster import KMeans

# Range of k values to test
k_values = range(1, 10)

# Calculate inertia for each k
inertia = []
for k in k_values:
    kmeans = KMeans(n_clusters=k, random_state=42)
    kmeans.fit(data)
    inertia.append(kmeans.inertia_)

# Plot the elbow curve
# plt.figure(figsize=(8, 5))
plt.plot(k_values, inertia, marker='o', linestyle='--')
plt.title('Elbow Curve for Optimal k')
plt.xlabel('Number of Clusters (k)')
plt.ylabel('Inertia')
plt.xticks(k_values)
plt.grid()
plt.show()