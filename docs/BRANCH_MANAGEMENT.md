# Branch Management System - Arrahnu Auction

## 🏢 **Complete Branch Network**

### **Active Branches (6 locations)**

#### 1. **Downtown Auction House** (Flagship)
- **Code**: DAH001
- **Location**: 123 Main Street, New York, NY 10001, USA
- **Contact**: +1 (555) 123-4567 | downtown@arrahnu.com
- **Manager**: Admin User
- **Status**: ✅ Active
- **Specialization**: Premium auction items and luxury collectibles
- **Hours**: Mon-Thu 9:00-18:00, Fri 9:00-20:00, Sat 10:00-17:00, Sun Closed
- **Coordinates**: 40.7128, -74.0060

#### 2. **Westside Gallery**
- **Code**: WSG002
- **Location**: 456 Art Avenue, Los Angeles, CA 90210, USA
- **Contact**: +1 (555) 987-6543 | westside@arrahnu.com
- **Manager**: Admin User
- **Status**: ✅ Active
- **Specialization**: Fine art, antiques, and contemporary pieces from renowned artists
- **Hours**: Mon-Fri 10:00-19:00, Fri 10:00-21:00, Sat 9:00-18:00, Sun 12:00-17:00
- **Coordinates**: 34.0522, -118.2437

#### 3. **Heritage Auction Center**
- **Code**: HAC003
- **Location**: 789 Historic Boulevard, Chicago, IL 60601, USA
- **Contact**: +1 (555) 456-7890 | heritage@arrahnu.com
- **Manager**: Admin User
- **Status**: ✅ Active
- **Specialization**: Vintage items, collectibles, and estate sales
- **Hours**: Mon-Fri 8:30-17:30, Fri 8:30-19:00, Sat 9:00-16:00, Sun Closed
- **Coordinates**: 41.8781, -87.6298

#### 4. **Pacific Coast Auctions**
- **Code**: PCA005
- **Location**: 789 Ocean Boulevard, San Francisco, CA 94102, USA
- **Contact**: +1 (555) 789-0123 | pacific@arrahnu.com
- **Manager**: Admin User
- **Status**: ✅ Active
- **Specialization**: Maritime antiques, vintage surfboards, and California art
- **Hours**: Mon-Fri 10:00-18:00, Fri 10:00-19:00, Sat 9:00-17:00, Sun 11:00-16:00
- **Coordinates**: 37.7749, -122.4194

#### 5. **Texas Treasures Auction**
- **Code**: TTA006
- **Location**: 456 Lone Star Drive, Austin, TX 73301, USA
- **Contact**: +1 (555) 456-7891 | texas@arrahnu.com
- **Manager**: Admin User
- **Status**: ✅ Active
- **Specialization**: Western memorabilia, oil paintings, and ranch collectibles
- **Hours**: Mon-Fri 9:00-17:00, Fri 9:00-18:00, Sat 8:00-16:00, Sun Closed
- **Coordinates**: 30.2672, -97.7431

#### 6. **Boston Antique Exchange**
- **Code**: BAE007
- **Location**: 234 Historic Harbor Way, Boston, MA 02101, USA
- **Contact**: +1 (555) 234-5678 | boston@arrahnu.com
- **Manager**: Admin User
- **Status**: ✅ Active
- **Specialization**: Colonial antiques, maritime artifacts, and Revolutionary War memorabilia
- **Hours**: Mon-Fri 9:30-17:30, Fri 9:30-18:30, Sat 10:00-17:00, Sun 12:00-16:00
- **Coordinates**: 42.3601, -71.0589

### **Inactive Branches (2 locations)**

#### 7. **Luxury Collectibles Hub**
- **Code**: LCH004
- **Location**: 321 Prestige Plaza, Miami, FL 33101, USA
- **Contact**: +1 (555) 321-0987 | luxury@arrahnu.com
- **Manager**: Admin User
- **Status**: ❌ Inactive (Under Renovation)
- **Specialization**: High-end collectibles, jewelry, and luxury items
- **Hours**: Currently Closed
- **Coordinates**: 25.7617, -80.1918

#### 8. **Rocky Mountain Collectibles**
- **Code**: RMC008
- **Location**: 567 Mountain View Road, Denver, CO 80201, USA
- **Contact**: +1 (555) 567-8901 | denver@arrahnu.com
- **Manager**: Admin User
- **Status**: ❌ Inactive (Expansion)
- **Specialization**: Native American artifacts, mining memorabilia, and outdoor gear
- **Hours**: Currently Closed
- **Coordinates**: 39.7392, -104.9903

## 📊 **Branch Statistics**

### **Geographic Distribution**
- **West Coast**: 2 branches (Los Angeles, San Francisco)
- **East Coast**: 2 branches (New York, Boston)
- **Central**: 2 branches (Chicago, Austin)
- **South**: 1 branch (Miami)
- **Mountain**: 1 branch (Denver)

### **Operational Status**
- **Total Branches**: 8
- **Active Branches**: 6 (75%)
- **Inactive Branches**: 2 (25%)
- **Under Renovation**: 1 (Miami)
- **Under Expansion**: 1 (Denver)

### **Specializations**
- **Luxury & Premium**: Downtown (NYC), Luxury Hub (Miami)
- **Art & Antiques**: Westside (LA), Heritage (Chicago), Boston Exchange
- **Regional Specialties**: Pacific Coast (Maritime), Texas (Western), Rocky Mountain (Native American)
- **Estate Sales**: Heritage Auction Center (Chicago)

## 🎯 **Management Features**

### **Admin Dashboard Access**
- **URL**: `/admin/branches`
- **Navigation**: Admin Sidebar → Branch Management
- **Quick Access**: Dashboard Quick Actions → "Manage Branches"

### **Branch Operations**
- **View Details**: Complete branch information display
- **Status Toggle**: Activate/Deactivate branches
- **Edit Information**: Update branch details (planned)
- **Delete Branch**: Remove branches with confirmation
- **Add New Branch**: Create new locations (planned)

### **Data Management**
- **Manager Assignment**: Link branches to admin users
- **Operating Hours**: JSON-based schedule system
- **Contact Information**: Phone and email per branch
- **Location Data**: GPS coordinates for mapping
- **Status Tracking**: Active/inactive with reasons

## 🔧 **Technical Implementation**

### **Database Schema**
```sql
branches table:
- id (primary key)
- name (branch name)
- code (unique identifier)
- address, city, state, postal_code, country
- phone, email
- manager_id (foreign key to users)
- is_active (boolean)
- description (text)
- latitude, longitude (decimal)
- operating_hours (JSON)
- created_at, updated_at
```

### **Model Features**
- **Relationships**: belongsTo(User) for manager
- **Accessors**: getFullAddressAttribute()
- **Business Logic**: isOpen() method
- **Scopes**: active(), inCity()

### **Routes Available**
```php
GET    /admin/branches              # List all branches
POST   /admin/branches              # Create new branch
PUT    /admin/branches/{branch}     # Update branch
POST   /admin/branches/{branch}/toggle-status  # Toggle active status
DELETE /admin/branches/{branch}     # Delete branch
```

## 🎨 **User Interface**

### **Branch List Table**
- **Branch Info**: Name, code, avatar
- **Location**: City, state, country
- **Manager**: Assigned admin with avatar
- **Status**: Active/Inactive badges
- **Contact**: Phone and email
- **Actions**: View, Toggle Status, Delete

### **Statistics Cards**
- **Total Branches**: Count of all locations
- **Active Branches**: Currently operational
- **Inactive Branches**: Under maintenance/expansion

### **Action Buttons**
- **View**: Branch details modal (planned)
- **Activate/Deactivate**: Toggle operational status
- **Delete**: Remove branch with confirmation

## 🚀 **Future Enhancements**

### **Planned Features**
- **Branch Details Modal**: Complete information editing
- **Operating Hours Editor**: Visual schedule management
- **Branch Analytics**: Performance metrics per location
- **Map Integration**: Visual branch location display
- **Branch Photos**: Image gallery for each location

### **Advanced Features**
- **Inventory Management**: Track items per branch
- **Staff Management**: Assign multiple staff per branch
- **Performance Metrics**: Revenue and auction statistics
- **Customer Reviews**: Branch-specific feedback
- **Event Scheduling**: Branch-specific auction events

## 📱 **Access Information**

### **Live Testing**
- **Branch Management**: http://127.0.0.1:8002/admin/branches
- **Admin Dashboard**: http://127.0.0.1:8002/admin/dashboard
- **Login**: admin@arrahnu.com / admin123

### **Navigation Path**
1. Login as admin
2. Access Admin Dashboard
3. Click "Branch Management" in sidebar
4. Or use "Manage Branches" quick action

## 🎯 **Key Benefits**

### **Operational Control**
- **Centralized Management**: All branches from single interface
- **Status Control**: Quick activation/deactivation
- **Contact Management**: Centralized communication info
- **Geographic Overview**: Multi-location coordination

### **Business Intelligence**
- **Performance Tracking**: Monitor branch operations
- **Resource Allocation**: Optimize staff and inventory
- **Expansion Planning**: Data-driven location decisions
- **Customer Service**: Location-specific support

### **Scalability**
- **Easy Expansion**: Add new branches seamlessly
- **Flexible Structure**: Adapt to different business models
- **Integration Ready**: Connect with other systems
- **Future-Proof**: Extensible architecture

Your auction platform now has a comprehensive branch management system with 8 sample locations across the United States, complete with realistic data and full administrative control! 🎉
