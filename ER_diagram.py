import matplotlib.pyplot as plt
import networkx as nx

# Create a directed graph to represent the ER diagram
G = nx.DiGraph()

# Nodes with types (entity, attribute)
entities = [
    ("users", "entity"),
    ("hostels", "entity"),
    ("complaints", "entity"),
    ("user_id", "attribute"),
    ("name", "attribute"),
    ("email", "attribute"),
    ("password", "attribute"),
    ("role", "attribute"),
    ("hostel_id", "attribute"),
    ("created_at", "attribute"),
    ("hostel_name", "attribute"),
    ("address", "attribute"),
    ("owner_id", "attribute"),
    ("complaint_id", "attribute"),
    ("room_no", "attribute"),
    ("complaint_text", "attribute"),
    ("category", "attribute"),
    ("sentiment", "attribute"),
    ("summary", "attribute"),
    ("status", "attribute")
]

# Add nodes
for node, node_type in entities:
    G.add_node(node, type=node_type)

# Relationships between tables and attributes
relationships = [
    ("users", "user_id"),
    ("users", "name"),
    ("users", "email"),
    ("users", "password"),
    ("users", "role"),
    ("users", "hostel_id"),
    ("users", "created_at"),
    
    ("hostels", "hostel_id"),
    ("hostels", "hostel_name"),
    ("hostels", "address"),
    ("hostels", "owner_id"),
    
    ("complaints", "complaint_id"),
    ("complaints", "user_id"),
    ("complaints", "hostel_id"),
    ("complaints", "room_no"),
    ("complaints", "complaint_text"),
    ("complaints", "category"),
    ("complaints", "sentiment"),
    ("complaints", "summary"),
    ("complaints", "status"),
    ("complaints", "created_at"),
    
    # Foreign key relationships
    ("users", "hostels"),
    ("hostels", "users"),  # owner_id link
    ("complaints", "users"),
    ("complaints", "hostels")
]

# Add edges to the graph
for src, dst in relationships:
    G.add_edge(src, dst)

# Layout and draw
pos = nx.spring_layout(G, k=1.3, iterations=50)
plt.figure(figsize=(16, 12))
nx.draw(G, pos, with_labels=True, node_color='skyblue', node_size=2000, font_size=10, font_weight='bold', edge_color='gray')
plt.title("ER Diagram: Complaint Management System", fontsize=16)
plt.show()
