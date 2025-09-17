import 'package:flutter/material.dart';
import '../core/theme.dart';

class StatusBadge extends StatelessWidget {
  final String status;
  final String? customText;
  final bool isCompact;

  const StatusBadge({
    super.key,
    required this.status,
    this.customText,
    this.isCompact = false,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final statusColor = AppTheme.getStatusColor(status);
    final backgroundColor = AppTheme.getStatusBackgroundColor(status);
    
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: isCompact ? 8 : 12,
        vertical: isCompact ? 4 : 6,
      ),
      decoration: BoxDecoration(
        color: backgroundColor,
        borderRadius: BorderRadius.circular(isCompact ? 8 : 12),
        border: Border.all(
          color: statusColor.withOpacity(0.3),
          width: 1,
        ),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: isCompact ? 6 : 8,
            height: isCompact ? 6 : 8,
            decoration: BoxDecoration(
              color: statusColor,
              shape: BoxShape.circle,
            ),
          ),
          SizedBox(width: isCompact ? 4 : 6),
          Text(
            customText ?? _getStatusText(status),
            style: (isCompact ? theme.textTheme.labelSmall : theme.textTheme.labelMedium)?.copyWith(
              color: statusColor,
              fontWeight: FontWeight.w600,
            ),
          ),
        ],
      ),
    );
  }

  String _getStatusText(String status) {
    switch (status.toLowerCase()) {
      case 'applied':
        return 'Applied';
      case 'screening':
        return 'Under Review';
      case 'interview':
        return 'Interview';
      case 'offer':
        return 'Offer';
      case 'rejected':
        return 'Rejected';
      default:
        return status.toUpperCase();
    }
  }
}

class StatusIcon extends StatelessWidget {
  final String status;
  final double size;

  const StatusIcon({
    super.key,
    required this.status,
    this.size = 20,
  });

  @override
  Widget build(BuildContext context) {
    final statusColor = AppTheme.getStatusColor(status);
    
    return Container(
      padding: const EdgeInsets.all(8),
      decoration: BoxDecoration(
        color: AppTheme.getStatusBackgroundColor(status),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Icon(
        _getStatusIcon(status),
        color: statusColor,
        size: size,
      ),
    );
  }

  IconData _getStatusIcon(String status) {
    switch (status.toLowerCase()) {
      case 'applied':
        return Icons.send_rounded;
      case 'screening':
        return Icons.visibility_rounded;
      case 'interview':
        return Icons.people_rounded;
      case 'offer':
        return Icons.celebration_rounded;
      case 'rejected':
        return Icons.cancel_rounded;
      default:
        return Icons.info_rounded;
    }
  }
}
