# Documentation Index

This directory contains detailed documentation for the Vehicle Booking System.

## Available Documents

1. **[Physical Data Model](./PHYSICAL_DATA_MODEL.md)**
   - Entity Relationship Diagram (ERD)
   - Table structures and relationships
   - Foreign key constraints
   - Indexes for performance optimization
   - Data types summary

2. **[Activity Diagram](./ACTIVITY_DIAGRAM.md)**
   - Booking process flow
   - Sequential approval process
   - Rejection flow
   - Key process steps
   - Business rules

## Viewing the Diagrams

### Option 1: GitHub/GitLab
If you push this repository to GitHub or GitLab, the Mermaid diagrams will render automatically in the markdown files.

### Option 2: VS Code
Install the "Markdown Preview Mermaid Support" extension to view diagrams in VS Code.

### Option 3: Online Tools
1. Copy the Mermaid code from the `.md` files
2. Paste into [Mermaid Live Editor](https://mermaid.live/)
3. View and export as PNG/SVG

### Option 4: Command Line
```bash
# Install Mermaid CLI
npm install -g @mermaid-js/mermaid-cli

# Generate PNG from markdown
mmdc -i docs/PHYSICAL_DATA_MODEL.md -o docs/physical_data_model.png
mmdc -i docs/ACTIVITY_DIAGRAM.md -o docs/activity_diagram.png
```

## Diagram Formats

All diagrams are written in **Mermaid** syntax, which is:
- Human-readable
- Version-controllable
- Renderable in many platforms
- Exportable to images

## Additional Resources

- [Mermaid Documentation](https://mermaid.js.org/)
- [Mermaid Live Editor](https://mermaid.live/)
- [PlantUML Alternative](http://www.plantuml.com/) (if you prefer PlantUML syntax)

